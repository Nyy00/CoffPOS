<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\User;
use App\Models\Transaction;
use App\Services\SimpleImageService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    protected $imageService;

    public function __construct(SimpleImageService $imageService)
    {
        $this->imageService = $imageService;
    }
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

        // Calculate additional statistics
        $totalExpenses = Expense::sum('amount');
        $monthlyExpenses = Expense::whereMonth('expense_date', Carbon::now()->month)
                                 ->whereYear('expense_date', Carbon::now()->year)
                                 ->sum('amount');
        $expensesCount = Expense::count();
        $averageExpense = $expensesCount > 0 ? $totalExpenses / $expensesCount : 0;

        return view('admin.expenses.index', compact(
            'expenses', 
            'categories', 
            'users', 
            'totalAmount', 
            'totalCount',
            'totalExpenses',
            'monthlyExpenses', 
            'expensesCount',
            'averageExpense'
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
            try {
                $uploadResult = $this->imageService->upload(
                    $request->file('receipt_image'), 
                    'receipts'
                );
                $validated['receipt_image'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Receipt upload failed: ' . $e->getMessage());
            }
        }

        $validated['user_id'] = auth()->id();

        try {
            $expense = Expense::create($validated);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create expense: ' . $e->getMessage());
        }

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
            try {
                // Delete old receipt if exists
                if ($expense->receipt_image) {
                    $this->imageService->delete($expense->receipt_image);
                }

                $uploadResult = $this->imageService->upload(
                    $request->file('receipt_image'), 
                    'receipts'
                );
                $validated['receipt_image'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Receipt upload failed: ' . $e->getMessage());
            }
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
        if ($expense->receipt_image) {
            $this->imageService->delete($expense->receipt_image);
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

        if ($expense->receipt_image) {
            $deleted = $this->imageService->delete($expense->receipt_image);
            
            if ($deleted) {
                $expense->update(['receipt_image' => null]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Receipt removed successfully'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No receipt to remove or deletion failed'
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
     * Get real-time dashboard data for managers
     */
    public function getDashboardData(Request $request)
    {
        $period = $request->get('period', 'today');
        
        // Calculate date ranges based on period
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default:
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
        }
        
        // Get revenue for the period
        $revenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');
        
        // Get expenses for the period
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
        
        // Expense by category for the period
        $expensesByCategory = Expense::selectRaw('category, SUM(amount) as total')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->groupBy('category')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => ucfirst($item->category),
                    'amount' => (float) $item->total
                ];
            });
        
        // Recent expenses (always show latest regardless of period)
        $recentExpenses = Expense::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($expense) {
                return [
                    'id' => $expense->id,
                    'description' => $expense->description,
                    'category' => ucfirst($expense->category),
                    'amount' => (float) $expense->amount,
                    'date' => $expense->expense_date ? $expense->expense_date->format('d M Y') : null,
                    'user' => $expense->user->name
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period,
                'revenue' => (float) $revenue,
                'expenses' => (float) $expenses,
                'profit' => (float) ($revenue - $expenses),
                'expenses_by_category' => $expensesByCategory,
                'recent_expenses' => $recentExpenses
            ],
            'timestamp' => now()->toISOString()
        ]);
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
                    $expense->expense_date ? $expense->expense_date->format('Y-m-d') : null,
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
     * Get profit/loss analysis data
     */
    public function getProfitLossData(Request $request)
    {
        $period = $request->get('period', '12months');
        $year = $request->get('year', Carbon::now()->year);
        
        switch ($period) {
            case '7days':
                $data = $this->getProfitLossWeekly();
                break;
            case '30days':
                $data = $this->getProfitLossDaily(30);
                break;
            case '12months':
                $data = $this->getProfitLossMonthly($year);
                break;
            default:
                $data = $this->getProfitLossMonthly($year);
        }

        return response()->json([
            'success' => true,
            'period' => $period,
            'year' => $year,
            'data' => $data,
            'summary' => $this->getProfitLossSummary($period, $year)
        ]);
    }

    /**
     * Get weekly profit/loss data
     */
    private function getProfitLossWeekly()
    {
        $startDate = Carbon::now()->subDays(7);
        $data = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            
            $revenue = Transaction::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
                
            $expenses = Expense::whereDate('expense_date', $date)
                ->sum('amount');
                
            $profit = $revenue - $expenses;
            
            $data[] = [
                'label' => $date->format('M d'),
                'date' => $date->format('Y-m-d'),
                'revenue' => (float) $revenue,
                'expenses' => (float) $expenses,
                'profit' => (float) $profit,
                'profit_margin' => $revenue > 0 ? (($profit / $revenue) * 100) : 0
            ];
        }

        return $data;
    }

    /**
     * Get daily profit/loss data for specified days
     */
    private function getProfitLossDaily($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            
            $revenue = Transaction::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
                
            $expenses = Expense::whereDate('expense_date', $date)
                ->sum('amount');
                
            $profit = $revenue - $expenses;
            
            $data[] = [
                'label' => $date->format('M d'),
                'date' => $date->format('Y-m-d'),
                'revenue' => (float) $revenue,
                'expenses' => (float) $expenses,
                'profit' => (float) $profit,
                'profit_margin' => $revenue > 0 ? (($profit / $revenue) * 100) : 0
            ];
        }

        return $data;
    }

    /**
     * Get monthly profit/loss data
     */
    private function getProfitLossMonthly($year)
    {
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            
            $revenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->sum('total_amount');
                
            $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
                ->sum('amount');
                
            $profit = $revenue - $expenses;
            
            $data[] = [
                'label' => $startDate->format('M Y'),
                'month' => $month,
                'year' => $year,
                'revenue' => (float) $revenue,
                'expenses' => (float) $expenses,
                'profit' => (float) $profit,
                'profit_margin' => $revenue > 0 ? (($profit / $revenue) * 100) : 0
            ];
        }

        return $data;
    }

    /**
     * Get profit/loss summary
     */
    private function getProfitLossSummary($period, $year)
    {
        switch ($period) {
            case '7days':
                $startDate = Carbon::now()->subDays(7);
                $endDate = Carbon::now();
                break;
            case '30days':
                $startDate = Carbon::now()->subDays(30);
                $endDate = Carbon::now();
                break;
            case '12months':
                $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
                $endDate = $startDate->copy()->endOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
        }

        $totalRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
            
        $totalProfit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? (($totalProfit / $totalRevenue) * 100) : 0;

        // Get expense breakdown by category
        $expensesByCategory = Expense::selectRaw('category, SUM(amount) as total')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category')
            ->map(function ($amount, $category) {
                return (float) $amount;
            })
            ->toArray();

        return [
            'period' => $period,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total_revenue' => (float) $totalRevenue,
            'total_expenses' => (float) $totalExpenses,
            'total_profit' => (float) $totalProfit,
            'profit_margin' => (float) $profitMargin,
            'expenses_by_category' => $expensesByCategory,
            'status' => $totalProfit >= 0 ? 'profitable' : 'loss'
        ];
    }

    /**
     * Search expenses (API endpoint)
     */
    public function search(Request $request)
    {
        $query = Expense::with('user:id,name');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 10);
        $expenses = $query->orderBy('expense_date', 'desc')
                         ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $expenses->items(),
            'pagination' => [
                'current_page' => $expenses->currentPage(),
                'last_page' => $expenses->lastPage(),
                'per_page' => $expenses->perPage(),
                'total' => $expenses->total(),
                'has_more' => $expenses->hasMorePages()
            ]
        ]);
    }

    /**
     * Filter expenses (API endpoint)
     */
    public function filter(Request $request)
    {
        $query = Expense::with('user:id,name');

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        // Amount range filter
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Has receipt filter
        if ($request->filled('has_receipt')) {
            if ($request->boolean('has_receipt')) {
                $query->whereNotNull('receipt_image');
            } else {
                $query->whereNull('receipt_image');
            }
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'expense_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['expense_date', 'amount', 'category', 'created_at'];
        
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 15);
        $expenses = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $expenses->items(),
            'pagination' => [
                'current_page' => $expenses->currentPage(),
                'last_page' => $expenses->lastPage(),
                'per_page' => $expenses->perPage(),
                'total' => $expenses->total(),
                'from' => $expenses->firstItem(),
                'to' => $expenses->lastItem()
            ],
            'filters_applied' => $request->only([
                'category', 'date_from', 'date_to', 'amount_min', 'amount_max', 
                'user_id', 'has_receipt', 'search', 'sort_by', 'sort_order'
            ])
        ]);
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
            if ($expense->receipt_image) {
                $this->imageService->delete($expense->receipt_image);
            }
        }

        $deletedCount = $expenses->delete();

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} expenses"
        ]);
    }
}