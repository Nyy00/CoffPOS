<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
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
        if ($request->filled('points_filter')) {
            switch ($request->points_filter) {
                case 'high':
                    $query->where('points', '>', 100);
                    break;
                case 'medium':
                    $query->whereBetween('points', [50, 100]);
                    break;
                case 'low':
                    $query->where('points', '<', 50);
                    break;
            }
        }

        // Alternative: Filter by specific points range (for API)
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
    public function store(CustomerRequest $request)
    {
        $validated = $request->validated();

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
    public function update(CustomerRequest $request, Customer $customer)
    {
        $validated = $request->validated();

        // FIX: Pastikan 'points' diambil dari request dan dimasukkan ke array validated
        // Ini untuk berjaga-jaga jika 'points' tidak ada di rules CustomerRequest
        if ($request->has('points')) {
            $validated['points'] = $request->input('points');
        }

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
     * Quick add customer for POS system (AJAX)
     */
    public function quickAdd(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20|unique:customers,phone',
                'email' => 'nullable|email|max:255|unique:customers,email',
            ]);

            $customer = Customer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'points' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer added successfully',
                'data' => $customer->only(['id', 'name', 'phone', 'email', 'points'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Quick add customer error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error adding customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quick customer info for POS
     */
    public function getQuickInfo(Customer $customer)
    {
        try {
            $customerData = [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'points' => $customer->points,
                'total_transactions' => $customer->transactions()->count(),
                'total_spent' => $customer->transactions()->sum('total_amount'),
                'last_transaction' => $customer->transactions()->latest()->first()?->created_at?->format('d M Y'),
            ];

            return response()->json([
                'success' => true,
                'data' => $customerData
            ]);

        } catch (\Exception $e) {
            \Log::error('Get quick info error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error getting customer info',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick search for POS system (AJAX)
     */
    public function quickSearch(Request $request)
    {
        try {
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
                ->orderBy('name')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'customers' => $customers
            ]);

        } catch (\Exception $e) {
            \Log::error('Quick search error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error searching customers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint for customer search (AJAX)
     */
    public function search(Request $request)
    {
        $query = Customer::withCount('transactions')
            ->withSum('transactions', 'total_amount');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 10);
        $customers = $query->orderBy('name')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $customers->items(),
            'pagination' => [
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
                'has_more' => $customers->hasMorePages()
            ]
        ]);
    }

    /**
     * API endpoint for advanced customer filtering
     */
    public function filter(Request $request)
    {
        $query = Customer::withCount('transactions')
            ->withSum('transactions', 'total_amount');

        // Points range filter
        if ($request->filled('points_filter')) {
            switch ($request->points_filter) {
                case 'high':
                    $query->where('points', '>', 100);
                    break;
                case 'medium':
                    $query->whereBetween('points', [50, 100]);
                    break;
                case 'low':
                    $query->where('points', '<', 50);
                    break;
            }
        }

        // Alternative: Filter by specific points range (for API)
        if ($request->filled('points_min')) {
            $query->where('points', '>=', $request->points_min);
        }
        if ($request->filled('points_max')) {
            $query->where('points', '<=', $request->points_max);
        }

        // Registration date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Transaction count filter
        if ($request->filled('min_transactions')) {
            $query->has('transactions', '>=', $request->min_transactions);
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedSorts = ['name', 'points', 'created_at', 'transactions_count'];
        
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 15);
        $customers = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $customers->items(),
            'pagination' => [
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
                'from' => $customers->firstItem(),
                'to' => $customers->lastItem()
            ],
            'filters_applied' => $request->only([
                'points_min', 'points_max', 'date_from', 'date_to', 
                'min_transactions', 'search', 'sort_by', 'sort_order'
            ])
        ]);
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

        // Log points change
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