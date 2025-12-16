<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses with filters
     */
    public function index(Request $request)
    {
        $query = Expense::with('user:id,name');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'expense_date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['expense_date', 'amount', 'category', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $expenses = $query->paginate(15)->withQueryString();

        // Get filter options
        $categories = Expense::distinct()->pluck('category')->filter();
        $users = User::select('id', 'name')->get();

        // Calculate totals for current filter
        $totalAmount = $query->sum('amount');
        $totalCount = $query->count();

        return view('admin.expenses.index', compact(
            'expenses', 
            'categories', 
            'users', 
            'totalAmount', 
            'totalCount'
        ));
    }

    /**
     * Show the form for creating a new expense
     */
    public function create()
    {
        $categories = ExpenseRequest::getCategoryOptions();
        $categoryDescriptions = ExpenseRequest::getCategoryDescriptions();

        return view('admin.expenses.create', compact('categories', 'categoryDescriptions'));
    }

    /**
     * Store a newly created expense in storage
     */
    public function store(ExpenseRequest $request)
    {
        $validated = $request->validated();

        // Handle receipt upload
        if ($request->hasFile('receipt_image')) {
            $receipt = $request->file('receipt_image');
            $receiptName = time() . '_' . Str::random(10) . '.' . $receipt->getClientOriginalExtension();
            $receiptPath = $receipt->storeAs('receipts', $receiptName, 'public');
            $validated['receipt_image'] = $receiptPath;
        }

        $validated['user_id'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified expense with receipt
     */
    public function show(Expense $expense)
    {
        $expense->load('user:id,name');

        // Get related expenses (same category, similar amount, or same date)
        $relatedExpenses = Expense::where('id', '!=', $expense->id)
            ->where(function ($query) use ($expense) {
                $query->where('category', $expense->category)
                      ->orWhereBetween('amount', [$expense->amount * 0.8, $expense->amount * 1.2])
                      ->orWhereDate('expense_date', $expense->expense_date);
            })
            ->with('user:id,name')
            ->limit(5)
            ->get();

        return view('admin.expenses.show', compact('expense', 'relatedExpenses'));
    }

    /**
     * Show the form for editing the specified expense
     */
    public function edit(Expense $expense)
    {
        // Only allow editing own expenses or admin can edit all
        if (!auth()->user()->isAdmin() && $expense->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $categories = ExpenseRequest::getCategoryOptions();
        $categoryDescriptions = ExpenseRequest::getCategoryDescriptions();

        return view('admin.expenses.edit', compact('expense', 'categories', 'categoryDescriptions'));
    }

    /**
     * Update the specified expense in storage
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        // Only allow editing own expenses or admin can edit all
        if (!auth()->user()->isAdmin() && $expense->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validated();

        // Handle receipt upload
        if ($request->hasFile('receipt_image')) {
            // Delete old receipt if exists
            if ($expense->receipt_image && Storage::disk('public')->exists($expense->receipt_image)) {
                Storage::disk('public')->delete($expense->receipt_image);
            }

            $receipt = $request->file('receipt_image');
            $receiptName = time() . '_' . Str::random(10) . '.' . $receipt->getClientOriginalExtension();
            $receiptPath = $receipt->storeAs('receipts', $receiptName, 'public');
            $validated['receipt_image'] = $receiptPath;
        }

        $expense->update($validated);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense from storage
     */
    public function destroy(Expense $expense)
    {
        // Only allow deleting own expenses or admin can delete all
        if (!auth()->user()->isAdmin() && $expense->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Delete receipt if exists
        if ($expense->receipt_image && Storage::disk('public')->exists($expense->receipt_image)) {
            Storage::disk('public')->delete($expense->receipt_image);
        }

        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Remove receipt from expense
     */
    public function removeReceipt(Expense $expense)
    {
        // Only allow editing own expenses or admin can edit all
        if (!auth()->user()->isAdmin() && $expense->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        if ($expense->receipt_image && Storage::disk('public')->exists($expense->receipt_image)) {
            Storage::disk('public')->delete($expense->receipt_image);
            $expense->update(['receipt_image' => null]);
            
            return response()->json([
                'success' => true,
                'message' => 'Receipt removed successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No receipt to remove'
        ]);
    }

    /**
     * Get expense statistics
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        
        $query = Expense::query();
        
        switch ($period) {
            case 'day':
                $query->whereDate('expense_date', today());
                break;
            case 'week':
                $query->whereBetween('expense_date', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'month':
                $query->whereMonth('expense_date', Carbon::now()->month)
                      ->whereYear('expense_date', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('expense_date', Carbon::now()->year);
                break;
        }

        $stats = [
            'total_amount' => $query->sum('amount'),
            'total_count' => $query->count(),
            'average_amount' => $query->avg('amount'),
            'by_category' => $query->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
                                  ->groupBy('category')
                                  ->get(),
            'recent_expenses' => Expense::with('user:id,name')
                                       ->latest()
                                       ->limit(5)
                                       ->get()
        ];

        return response()->json($stats);
    }

    /**
     * Export expenses to CSV
     */
    public function export(Request $request)
    {
        $query = Expense::with('user:id,name');

        // Apply same filters as index
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();

        $filename = 'expenses_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Category', 'Description', 'Amount', 
                'Expense Date', 'User', 'Has Receipt', 'Created Date'
            ]);

            // CSV data
            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->id,
                    ucfirst($expense->category),
                    $expense->description,
                    $expense->amount,
                    $expense->expense_date->format('Y-m-d'),
                    $expense->user->name,
                    $expense->receipt_image ? 'Yes' : 'No',
                    $expense->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get monthly expense chart data
     */
    public function getChartData(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        
        $monthlyData = Expense::selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
            ->whereYear('expense_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = [
                'month' => Carbon::create()->month($i)->format('M'),
                'amount' => $monthlyData->get($i, 0)
            ];
        }

        return response()->json($chartData);
    }

    /**
     * Bulk delete expenses
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'expense_ids' => 'required|array',
            'expense_ids.*' => 'exists:expenses,id'
        ]);

        $expenses = Expense::whereIn('id', $validated['expense_ids']);

        // Check permissions for each expense
        if (!auth()->user()->isAdmin()) {
            $expenses->where('user_id', auth()->id());
        }

        $expensesToDelete = $expenses->get();

        // Delete receipt files
        foreach ($expensesToDelete as $expense) {
            if ($expense->receipt_image && Storage::disk('public')->exists($expense->receipt_image)) {
                Storage::disk('public')->delete($expense->receipt_image);
            }
        }

        $deletedCount = $expenses->delete();

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} expenses"
        ]);
    }
}