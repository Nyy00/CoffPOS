<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with enhanced statistics.
     */
    public function index()
    {
        // Get comprehensive statistics
        $stats = $this->getComprehensiveStats();
        
        // Get chart data
        $chartData = [
            'revenue' => $this->getRevenueChartData(),
            'sales' => $this->getSalesChartData(),
            'topProducts' => $this->getTopProductsData(),
            'paymentMethods' => $this->getPaymentMethodsData()
        ];
        
        // Get recent activities
        $recentTransactions = Transaction::with(['customer', 'user', 'transactionItems'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get low stock alerts
        $lowStockProducts = Product::where('stock', '<', 10)
            ->where('is_available', true)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();
        
        // Get top customers
        $topCustomers = Customer::select('customers.*', DB::raw('SUM(transactions.total) as total_spent'))
            ->join('transactions', 'customers.id', '=', 'transactions.customer_id')
            ->where('transactions.status', 'completed')
            ->whereMonth('transactions.created_at', now()->month)
            ->groupBy('customers.id')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get();
        
        // Check user role and return appropriate view
        if (auth()->user()->isManager()) {
            // Define today for manager-specific calculations
            $today = Carbon::today();
            
            // Get manager-specific data
            $recentExpenses = Expense::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
                
            // Add expense data to stats
            $stats['today']['expenses'] = Expense::whereDate('expense_date', $today)->sum('amount');
            $stats['today']['profit'] = $stats['today']['revenue'] - $stats['today']['expenses'];
            $stats['pending_expenses'] = 0; // Placeholder for approval system
            
            return view('admin.dashboard-manager', compact(
                'stats',
                'recentExpenses'
            ));
        }
        
        return view('admin.dashboard', compact(
            'stats',
            'chartData',
            'recentTransactions',
            'lowStockProducts',
            'topCustomers'
        ));
    }

    /**
     * Get comprehensive dashboard statistics
     */
    private function getComprehensiveStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Today's stats
        $todayStats = [
            'revenue' => Transaction::whereDate('created_at', $today)->where('status', 'completed')->sum('total_amount'),
            'transactions' => Transaction::whereDate('created_at', $today)->where('status', 'completed')->count(),
            'customers' => Transaction::whereDate('created_at', $today)->where('status', 'completed')->distinct('customer_id')->count('customer_id'),
            'items_sold' => DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->whereDate('transactions.created_at', $today)
                ->where('transactions.status', 'completed')
                ->sum('transaction_items.quantity')
        ];

        // This month's stats
        $thisMonthStats = [
            'revenue' => Transaction::where('created_at', '>=', $thisMonth)->where('status', 'completed')->sum('total_amount'),
            'transactions' => Transaction::where('created_at', '>=', $thisMonth)->where('status', 'completed')->count(),
            'expenses' => Expense::where('expense_date', '>=', $thisMonth)->sum('amount'),
            'customers' => Transaction::where('created_at', '>=', $thisMonth)->where('status', 'completed')->distinct('customer_id')->count('customer_id')
        ];

        // Last month's stats for comparison
        $lastMonthStats = [
            'revenue' => Transaction::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->where('status', 'completed')->sum('total_amount'),
            'transactions' => Transaction::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->where('status', 'completed')->count(),
            'expenses' => Expense::whereBetween('expense_date', [$lastMonth, $lastMonthEnd])->sum('amount')
        ];

        // Calculate growth percentages
        $revenueGrowth = $lastMonthStats['revenue'] > 0 
            ? (($thisMonthStats['revenue'] - $lastMonthStats['revenue']) / $lastMonthStats['revenue']) * 100 
            : 0;

        $transactionGrowth = $lastMonthStats['transactions'] > 0 
            ? (($thisMonthStats['transactions'] - $lastMonthStats['transactions']) / $lastMonthStats['transactions']) * 100 
            : 0;

        // Total counts
        $totals = [
            'products' => Product::count(),
            'active_products' => Product::where('is_available', true)->count(),
            'customers' => Customer::count(),
            'categories' => Category::count(),
            'users' => User::count(),
            'low_stock_count' => Product::where('stock', '<', 10)->where('is_available', true)->count()
        ];

        // Calculate profit
        $thisMonthProfit = $thisMonthStats['revenue'] - $thisMonthStats['expenses'];
        $lastMonthProfit = $lastMonthStats['revenue'] - $lastMonthStats['expenses'];
        $profitGrowth = $lastMonthProfit > 0 
            ? (($thisMonthProfit - $lastMonthProfit) / $lastMonthProfit) * 100 
            : 0;

        return [
            'today' => $todayStats,
            'this_month' => $thisMonthStats,
            'last_month' => $lastMonthStats,
            'growth' => [
                'revenue' => $revenueGrowth,
                'transactions' => $transactionGrowth,
                'profit' => $profitGrowth
            ],
            'totals' => $totals,
            'profit' => [
                'this_month' => $thisMonthProfit,
                'last_month' => $lastMonthProfit
            ]
        ];
    }
    
    /**
     * Get real-time dashboard statistics (API endpoint).
     */
    public function getStats()
    {
        $stats = $this->getComprehensiveStats();
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get revenue chart data for different periods
     */
    private function getRevenueChartData($period = '7days')
    {
        switch ($period) {
            case '24hours':
                $data = Transaction::select(
                        DB::raw('HOUR(created_at) as hour'),
                        DB::raw('SUM(total_amount) as revenue')
                    )
                    ->whereDate('created_at', today())
                    ->where('status', 'completed')
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => sprintf('%02d:00', $item->hour),
                            'value' => (float) $item->revenue
                        ];
                    });
                break;

            case '7days':
                $data = Transaction::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('SUM(total_amount) as revenue')
                    )
                    ->where('created_at', '>=', now()->subDays(7))
                    ->where('status', 'completed')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => Carbon::parse($item->date)->format('M d'),
                            'value' => (float) $item->revenue
                        ];
                    });
                break;

            case '30days':
                $data = Transaction::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('SUM(total_amount) as revenue')
                    )
                    ->where('created_at', '>=', now()->subDays(30))
                    ->where('status', 'completed')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => Carbon::parse($item->date)->format('M d'),
                            'value' => (float) $item->revenue
                        ];
                    });
                break;

            case '12months':
                $data = Transaction::select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('SUM(total_amount) as revenue')
                    )
                    ->where('created_at', '>=', now()->subMonths(12))
                    ->where('status', 'completed')
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                            'value' => (float) $item->revenue
                        ];
                    });
                break;

            default:
                $data = collect();
        }

        return $data;
    }

    /**
     * Get sales trend data
     */
    private function getSalesChartData()
    {
        return Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as transactions'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'transactions' => (int) $item->transactions,
                    'revenue' => (float) $item->revenue
                ];
            });
    }

    /**
     * Get top products data
     */
    private function getTopProductsData($limit = 10)
    {
        return Product::select(
                'products.name',
                'products.price',
                'products.stock',
                'products.image',
                DB::raw('SUM(transaction_items.quantity) as total_sold'),
                DB::raw('SUM(transaction_items.subtotal) as total_revenue')
            )
            ->join('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->whereMonth('transactions.created_at', now()->month)
            ->groupBy('products.id', 'products.name', 'products.price', 'products.stock', 'products.image')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'price' => (float) $item->price,
                    'stock' => (int) $item->stock,
                    'image' => $item->image,
                    'sold' => (int) $item->total_sold,
                    'revenue' => (float) $item->total_revenue
                ];
            });
    }

    /**
     * Get payment methods distribution
     */
    private function getPaymentMethodsData()
    {
        return Transaction::select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->groupBy('payment_method')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => ucfirst(str_replace('_', ' ', $item->payment_method)),
                    'count' => (int) $item->count,
                    'total' => (float) $item->total,
                    'percentage' => 0 // Will be calculated on frontend
                ];
            });
    }
    
    /**
     * Get chart data for dashboard with enhanced options.
     */
    public function getChartData(Request $request, $type)
    {
        $period = $request->get('period', '7days');
        
        switch ($type) {
            case 'revenue':
                $data = $this->getRevenueChartData($period);
                break;
                
            case 'sales':
                $data = $this->getSalesChartData();
                break;
                
            case 'products':
                $data = $this->getTopProductsData();
                break;
                
            case 'payment-methods':
                $data = $this->getPaymentMethodsData();
                break;
                
            case 'categories':
                $data = Category::select(
                        'categories.name',
                        DB::raw('COUNT(products.id) as product_count'),
                        DB::raw('COALESCE(SUM(transaction_items.quantity), 0) as total_sold')
                    )
                    ->leftJoin('products', 'categories.id', '=', 'products.category_id')
                    ->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
                    ->leftJoin('transactions', function($join) {
                        $join->on('transaction_items.transaction_id', '=', 'transactions.id')
                             ->where('transactions.status', '=', 'completed')
                             ->whereMonth('transactions.created_at', '=', now()->month);
                    })
                    ->groupBy('categories.id', 'categories.name')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'name' => $item->name,
                            'product_count' => (int) $item->product_count,
                            'total_sold' => (int) $item->total_sold
                        ];
                    });
                break;

            case 'hourly-sales':
                $data = Transaction::select(
                        DB::raw('HOUR(created_at) as hour'),
                        DB::raw('COUNT(*) as transactions'),
                        DB::raw('SUM(total_amount) as revenue')
                    )
                    ->whereDate('created_at', today())
                    ->where('status', 'completed')
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'hour' => sprintf('%02d:00', $item->hour),
                            'transactions' => (int) $item->transactions,
                            'revenue' => (float) $item->revenue
                        ];
                    });
                break;

            case 'customer-growth':
                $data = Customer::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as new_customers')
                    )
                    ->where('created_at', '>=', now()->subDays(30))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'date' => $item->date,
                            'new_customers' => (int) $item->new_customers
                        ];
                    });
                break;
                
            default:
                $data = [];
        }
        
        return response()->json([
            'success' => true,
            'type' => $type,
            'period' => $period,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Get recent activities with enhanced data.
     */
    public function getRecentActivities(Request $request)
    {
        $limit = $request->get('limit', 20);
        $type = $request->get('type', 'all'); // all, transactions, expenses, customers
        
        $activities = collect();
        
        if ($type === 'all' || $type === 'transactions') {
            $transactions = Transaction::with(['customer', 'user'])
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'type' => 'transaction',
                        'icon' => 'shopping-cart',
                        'title' => "Transaction {$transaction->transaction_code}",
                        'description' => "By {$transaction->user->name}" . 
                                       ($transaction->customer ? " for {$transaction->customer->name}" : " (Walk-in)"),
                        'amount' => $transaction->total,
                        'status' => $transaction->status,
                        'time' => $transaction->created_at,
                        'time_human' => $transaction->created_at->diffForHumans(),
                        'url' => route('admin.transactions.show', $transaction->id)
                    ];
                });
            
            $activities = $activities->merge($transactions);
        }
        
        if ($type === 'all' || $type === 'expenses') {
            $expenses = Expense::with('user')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($expense) {
                    return [
                        'id' => $expense->id,
                        'type' => 'expense',
                        'icon' => 'credit-card',
                        'title' => "Expense: {$expense->category}",
                        'description' => "By {$expense->user->name} - {$expense->description}",
                        'amount' => -$expense->amount, // Negative for expenses
                        'status' => 'completed',
                        'time' => $expense->created_at,
                        'time_human' => $expense->created_at->diffForHumans(),
                        'url' => route('admin.expenses.show', $expense->id)
                    ];
                });
            
            $activities = $activities->merge($expenses);
        }
        
        if ($type === 'all' || $type === 'customers') {
            $customers = Customer::orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'type' => 'customer',
                        'icon' => 'user-plus',
                        'title' => "New Customer: {$customer->name}",
                        'description' => "Phone: {$customer->phone}",
                        'amount' => null,
                        'status' => 'active',
                        'time' => $customer->created_at,
                        'time_human' => $customer->created_at->diffForHumans(),
                        'url' => route('admin.customers.show', $customer->id)
                    ];
                });
            
            $activities = $activities->merge($customers);
        }
        
        // Sort by time and limit
        $activities = $activities->sortByDesc('time')->take($limit)->values();
        
        return response()->json([
            'success' => true,
            'data' => $activities,
            'type' => $type,
            'limit' => $limit,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get top customers data
     */
    public function getTopCustomers(Request $request)
    {
        $period = $request->get('period', 'month'); // month, quarter, year
        $limit = $request->get('limit', 10);
        
        $query = Customer::select(
                'customers.*',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.total) as total_spent'),
                DB::raw('AVG(transactions.total) as avg_transaction')
            )
            ->join('transactions', 'customers.id', '=', 'transactions.customer_id')
            ->where('transactions.status', 'completed');
        
        switch ($period) {
            case 'week':
                $query->where('transactions.created_at', '>=', now()->startOfWeek());
                break;
            case 'month':
                $query->where('transactions.created_at', '>=', now()->startOfMonth());
                break;
            case 'quarter':
                $query->where('transactions.created_at', '>=', now()->startOfQuarter());
                break;
            case 'year':
                $query->where('transactions.created_at', '>=', now()->startOfYear());
                break;
        }
        
        $customers = $query->groupBy('customers.id')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'points' => $customer->points,
                    'transaction_count' => (int) $customer->transaction_count,
                    'total_spent' => (float) $customer->total_spent,
                    'avg_transaction' => (float) $customer->avg_transaction
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $customers,
            'period' => $period,
            'limit' => $limit
        ]);
    }

    /**
     * Get low stock alerts
     */
    public function getLowStockAlerts()
    {
        $lowStockProducts = Product::where('stock', '<', 10)
            ->where('is_available', true)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'No Category',
                    'current_stock' => $product->stock,
                    'price' => $product->price,
                    'status' => $product->stock === 0 ? 'out_of_stock' : 'low_stock',
                    'urgency' => $product->stock === 0 ? 'critical' : ($product->stock < 5 ? 'high' : 'medium')
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $lowStockProducts,
            'total_alerts' => $lowStockProducts->count(),
            'critical_count' => $lowStockProducts->where('status', 'out_of_stock')->count(),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeek = Carbon::now()->startOfWeek();
        $lastWeek = Carbon::now()->subWeek()->startOfWeek();
        
        $metrics = [
            'daily_comparison' => [
                'today' => [
                    'revenue' => Transaction::whereDate('created_at', $today)->where('status', 'completed')->sum('total_amount'),
                    'transactions' => Transaction::whereDate('created_at', $today)->where('status', 'completed')->count(),
                ],
                'yesterday' => [
                    'revenue' => Transaction::whereDate('created_at', $yesterday)->where('status', 'completed')->sum('total_amount'),
                    'transactions' => Transaction::whereDate('created_at', $yesterday)->where('status', 'completed')->count(),
                ]
            ],
            'weekly_comparison' => [
                'this_week' => [
                    'revenue' => Transaction::where('created_at', '>=', $thisWeek)->where('status', 'completed')->sum('total_amount'),
                    'transactions' => Transaction::where('created_at', '>=', $thisWeek)->where('status', 'completed')->count(),
                ],
                'last_week' => [
                    'revenue' => Transaction::whereBetween('created_at', [$lastWeek, $lastWeek->copy()->endOfWeek()])->where('status', 'completed')->sum('total_amount'),
                    'transactions' => Transaction::whereBetween('created_at', [$lastWeek, $lastWeek->copy()->endOfWeek()])->where('status', 'completed')->count(),
                ]
            ],
            'average_metrics' => [
                'transaction_value' => Transaction::where('status', 'completed')->whereMonth('created_at', now()->month)->avg('total'),
                'items_per_transaction' => DB::table('transaction_items')
                    ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                    ->where('transactions.status', 'completed')
                    ->whereMonth('transactions.created_at', now()->month)
                    ->avg('transaction_items.quantity'),
                'customer_retention' => $this->calculateCustomerRetention()
            ]
        ];
        
        return response()->json([
            'success' => true,
            'data' => $metrics,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Calculate customer retention rate
     */
    private function calculateCustomerRetention()
    {
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        
        $customersThisMonth = Transaction::where('created_at', '>=', $thisMonth)
            ->where('status', 'completed')
            ->whereNotNull('customer_id')
            ->distinct('customer_id')
            ->count();
        
        $returningCustomers = Transaction::where('created_at', '>=', $thisMonth)
            ->where('status', 'completed')
            ->whereNotNull('customer_id')
            ->whereIn('customer_id', function($query) use ($lastMonth, $thisMonth) {
                $query->select('customer_id')
                    ->from('transactions')
                    ->whereBetween('created_at', [$lastMonth, $thisMonth])
                    ->where('status', 'completed')
                    ->whereNotNull('customer_id');
            })
            ->distinct('customer_id')
            ->count();
        
        return $customersThisMonth > 0 ? ($returningCustomers / $customersThisMonth) * 100 : 0;
    }
}