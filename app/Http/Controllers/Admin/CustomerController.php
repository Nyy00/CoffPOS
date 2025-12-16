<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers with search and filter
     */
    public function index(Request $request)
    {
        $query = Customer::withCount('transactions')
            ->withSum('transactions', 'total');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by points range
        if ($request->filled('points_min')) {
            $query->where('points', '>=', $request->points_min);
        }
        if ($request->filled('points_max')) {
            $query->where('points', '<=', $request->points_max);
        }

        // Filter by registration date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['name', 'points', 'created_at', 'transactions_count'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'email' => 'nullable|email|unique:customers,email',
            'address' => 'nullable|string',
            'points' => 'nullable|integer|min:0'
        ]);

        $validated['points'] = $validated['points'] ?? 0;

        Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer with transaction history
     */
    public function show(Customer $customer)
    {
        // Load transactions with related data
        $transactions = $customer->transactions()
            ->with(['user', 'transactionItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate customer statistics
        $totalTransactions = $customer->transactions()->count();
        $totalSpent = $customer->transactions()->sum('total');
        $averageTransaction = $totalTransactions > 0 ? $totalSpent / $totalTransactions : 0;
        $lastTransaction = $customer->transactions()->latest()->first();

        // Points history (you might want to create a separate points_history table)
        $pointsEarned = $customer->transactions()->sum('total') * 0.01; // 1% of total spent
        $pointsUsed = 0; // This would come from a points usage tracking system

        // Favorite products
        $favoriteProducts = $customer->transactions()
            ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(transaction_items.quantity) as total_quantity')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('admin.customers.show', compact(
            'customer',
            'transactions',
            'totalTransactions',
            'totalSpent',
            'averageTransaction',
            'lastTransaction',
            'pointsEarned',
            'pointsUsed',
            'favoriteProducts'
        ));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('customers')->ignore($customer->id)
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('customers')->ignore($customer->id)
            ],
            'address' => 'nullable|string',
            'points' => 'nullable|integer|min:0'
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage
     */
    public function destroy(Customer $customer)
    {
        // Check if customer has transactions
        if ($customer->transactions()->exists()) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Cannot delete customer. Customer has transaction history.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * API endpoint for customer search (AJAX)
     */
    public function search(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->select('id', 'name', 'phone', 'email', 'points')
            ->limit(10)
            ->get();

        return response()->json($customers);
    }

    /**
     * Get customer transaction history (AJAX)
     */
    public function getTransactionHistory(Customer $customer)
    {
        $transactions = $customer->transactions()
            ->with(['user:id,name', 'transactionItems.product:id,name'])
            ->select('id', 'transaction_code', 'total', 'payment_method', 'created_at', 'user_id')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($transactions);
    }

    /**
     * Update customer points
     */
    public function updatePoints(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:0',
            'action' => 'required|in:add,subtract,set',
            'reason' => 'nullable|string|max:255'
        ]);

        $oldPoints = $customer->points;

        switch ($validated['action']) {
            case 'add':
                $customer->increment('points', $validated['points']);
                break;
            case 'subtract':
                $customer->decrement('points', $validated['points']);
                break;
            case 'set':
                $customer->update(['points' => $validated['points']]);
                break;
        }

        // Log points change (you might want to create a points_log table)
        \Log::info("Customer points updated", [
            'customer_id' => $customer->id,
            'old_points' => $oldPoints,
            'new_points' => $customer->fresh()->points,
            'action' => $validated['action'],
            'reason' => $validated['reason'] ?? 'Manual adjustment'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Points updated successfully',
            'new_points' => $customer->fresh()->points
        ]);
    }

    /**
     * Export customers to CSV
     */
    public function export(Request $request)
    {
        $customers = Customer::withCount('transactions')
            ->withSum('transactions', 'total')
            ->get();

        $filename = 'customers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Phone', 'Email', 'Address', 
                'Points', 'Total Transactions', 'Total Spent', 'Registered Date'
            ]);

            // CSV data
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->phone,
                    $customer->email,
                    $customer->address,
                    $customer->points,
                    $customer->transactions_count,
                    $customer->transactions_sum_total ?? 0,
                    $customer->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}