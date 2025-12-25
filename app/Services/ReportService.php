<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class ReportService
{
    /**
     * Generate daily sales report
     */
    public function generateDailyReport($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        
        // Get transactions for the day
        $transactions = Transaction::with(['customer', 'user', 'transactionItems.product'])
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Calculate totals
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $totalItems = $transactions->sum(function ($transaction) {
            return $transaction->transactionItems->sum('quantity');
        });
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        
        // Payment method breakdown
        $paymentMethods = $transactions->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('total_amount'),
                'percentage' => 0 // Will be calculated later
            ];
        });
        
        // Calculate percentages
        foreach ($paymentMethods as $method => $data) {
            $paymentMethods[$method]['percentage'] = $totalRevenue > 0 
                ? round(($data['total'] / $totalRevenue) * 100, 2) 
                : 0;
        }
        
        // Hourly breakdown
        $hourlyBreakdown = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('H');
        })->map(function ($group) {
            return [
                'transactions' => $group->count(),
                'revenue' => $group->sum('total_amount')
            ];
        })->sortKeys();
        
        // Top products
        $topProducts = $this->getTopProductsForPeriod($date, $date);
        
        // Top customers
        $topCustomers = $transactions->where('customer_id', '!=', null)
            ->groupBy('customer_id')
            ->map(function ($group) {
                $customer = $group->first()->customer;
                return [
                    'customer' => $customer,
                    'transactions' => $group->count(),
                    'total_spent' => $group->sum('total_amount')
                ];
            })
            ->sortByDesc('total_spent')
            ->take(10)
            ->values();
        
        // Get expenses for the day
        $expenses = Expense::with('user')
            ->whereDate('expense_date', $date)
            ->get();
        
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;
        
        return [
            'date' => $date,
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_transactions' => $totalTransactions,
                'total_items' => $totalItems,
                'average_transaction' => $averageTransaction,
                'total_expenses' => $totalExpenses,
                'net_profit' => $netProfit
            ],
            'transactions' => $transactions,
            'payment_methods' => $paymentMethods,
            'hourly_breakdown' => $hourlyBreakdown,
            'top_products' => $topProducts,
            'top_customers' => $topCustomers,
            'expenses' => $expenses
        ];
    }
    
    /**
     * Generate monthly sales report
     */
    public function generateMonthlyReport($month = null, $year = null)
    {
        $month = $month ?: Carbon::now()->month;
        $year = $year ?: Carbon::now()->year;
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // Get transactions for the month
        $transactions = Transaction::with(['customer', 'user', 'transactionItems.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();
        
        // Calculate totals
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $totalItems = $transactions->sum(function ($transaction) {
            return $transaction->transactionItems->sum('quantity');
        });
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        
        // Daily breakdown
        $dailyBreakdown = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('Y-m-d');
        })->map(function ($group) {
            return [
                'transactions' => $group->count(),
                'revenue' => $group->sum('total_amount')
            ];
        })->sortKeys();
        
        // Weekly breakdown
        $weeklyBreakdown = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('W');
        })->map(function ($group) {
            return [
                'week' => 'Week ' . $group->first()->created_at->format('W'),
                'transactions' => $group->count(),
                'revenue' => $group->sum('total_amount')
            ];
        })->sortKeys();
        
        // Payment method breakdown
        $paymentMethods = $transactions->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('total_amount'),
                'percentage' => 0
            ];
        });
        
        foreach ($paymentMethods as $method => $data) {
            $paymentMethods[$method]['percentage'] = $totalRevenue > 0 
                ? round(($data['total'] / $totalRevenue) * 100, 2) 
                : 0;
        }
        
        // Top products for the month
        $topProducts = $this->getTopProductsForPeriod($startDate, $endDate);
        
        // Customer analysis
        $customerStats = $this->getCustomerStatsForPeriod($startDate, $endDate);
        
        // Get expenses for the month
        $expenses = Expense::with('user')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->get();
        
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;
        
        // Expense breakdown by category
        $expensesByCategory = $expenses->groupBy('category')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount')
            ];
        });
        
        // Compare with previous month
        $prevStartDate = $startDate->copy()->subMonth()->startOfMonth();
        $prevEndDate = $prevStartDate->copy()->endOfMonth();
        $prevMonthRevenue = Transaction::whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->where('status', 'completed')
            ->sum('total_amount');
        
        $revenueGrowth = $prevMonthRevenue > 0 
            ? round((($totalRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100, 2)
            : 0;
        
        return [
            'period' => [
                'month' => $month,
                'year' => $year,
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_transactions' => $totalTransactions,
                'total_items' => $totalItems,
                'average_transaction' => $averageTransaction,
                'total_expenses' => $totalExpenses,
                'net_profit' => $netProfit,
                'revenue_growth' => $revenueGrowth
            ],
            'daily_breakdown' => $dailyBreakdown,
            'weekly_breakdown' => $weeklyBreakdown,
            'payment_methods' => $paymentMethods,
            'top_products' => $topProducts,
            'customer_stats' => $customerStats,
            'expenses_by_category' => $expensesByCategory
        ];
    }
    
    /**
     * Generate product performance report
     */
    public function generateProductReport($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();
        
        // Get all products with sales data
        $products = Product::with(['category', 'transactionItems' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', 'completed');
            });
        }])->get();
        
        $productStats = $products->map(function ($product) {
            $totalSold = $product->transactionItems->sum('quantity');
            $totalRevenue = $product->transactionItems->sum('subtotal');
            $totalCost = $totalSold * $product->cost;
            $profit = $totalRevenue - $totalCost;
            $profitMargin = $totalRevenue > 0 ? ($profit / $totalRevenue) * 100 : 0;
            
            return [
                'product' => $product,
                'quantity_sold' => $totalSold,
                'revenue' => $totalRevenue,
                'cost' => $totalCost,
                'profit' => $profit,
                'profit_margin' => $profitMargin,
                'current_stock' => $product->stock,
                'stock_status' => $this->getStockStatus($product->stock)
            ];
        });
        
        // Sort by revenue
        $topPerformers = $productStats->sortByDesc('revenue')->take(20);
        $lowPerformers = $productStats->where('quantity_sold', '>', 0)->sortBy('revenue')->take(10);
        $noSales = $productStats->where('quantity_sold', 0);
        
        // Category performance
        $categoryStats = $products->groupBy('category.name')->map(function ($group) {
            $totalSold = $group->sum(function ($product) {
                return $product->transactionItems->sum('quantity');
            });
            $totalRevenue = $group->sum(function ($product) {
                return $product->transactionItems->sum('subtotal');
            });
            
            return [
                'products_count' => $group->count(),
                'quantity_sold' => $totalSold,
                'revenue' => $totalRevenue
            ];
        })->sortByDesc('revenue');
        
        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'summary' => [
                'total_products' => $products->count(),
                'products_with_sales' => $productStats->where('quantity_sold', '>', 0)->count(),
                'products_without_sales' => $noSales->count(),
                'total_revenue' => $productStats->sum('revenue'),
                'total_profit' => $productStats->sum('profit')
            ],
            'top_performers' => $topPerformers,
            'low_performers' => $lowPerformers,
            'no_sales' => $noSales,
            'category_stats' => $categoryStats
        ];
    }
    
    /**
     * Generate stock report
     */
    public function generateStockReport()
    {
        $products = Product::with('category')->get();
        
        $stockStats = $products->map(function ($product) {
            return [
                'product' => $product,
                'current_stock' => $product->stock,
                'stock_value' => $product->stock * $product->cost,
                'retail_value' => $product->stock * $product->price,
                'potential_profit' => ($product->price - $product->cost) * $product->stock,
                'stock_status' => $this->getStockStatus($product->stock)
            ];
        });
        
        // Categorize by stock status
        $outOfStock = $stockStats->where('current_stock', 0);
        $lowStock = $stockStats->where('current_stock', '>', 0)->where('current_stock', '<', 10);
        $adequateStock = $stockStats->where('current_stock', '>=', 10)->where('current_stock', '<', 50);
        $highStock = $stockStats->where('current_stock', '>=', 50);
        
        // Category breakdown
        $categoryBreakdown = $products->groupBy('category.name')->map(function ($group) {
            $totalStock = $group->sum('stock');
            $totalValue = $group->sum(function ($product) {
                return $product->stock * $product->cost;
            });
            
            return [
                'products_count' => $group->count(),
                'total_stock' => $totalStock,
                'total_value' => $totalValue,
                'out_of_stock' => $group->where('stock', 0)->count(),
                'low_stock' => $group->where('stock', '>', 0)->where('stock', '<', 10)->count()
            ];
        });
        
        return [
            'generated_at' => now(),
            'summary' => [
                'total_products' => $products->count(),
                'total_stock_value' => $stockStats->sum('stock_value'),
                'total_retail_value' => $stockStats->sum('retail_value'),
                'potential_profit' => $stockStats->sum('potential_profit'),
                'out_of_stock_count' => $outOfStock->count(),
                'low_stock_count' => $lowStock->count()
            ],
            'out_of_stock' => $outOfStock,
            'low_stock' => $lowStock,
            'adequate_stock' => $adequateStock,
            'high_stock' => $highStock,
            'category_breakdown' => $categoryBreakdown
        ];
    }
    
    /**
     * Generate profit & loss report
     */
    public function generateProfitLossReport($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();
        
        // Revenue calculation
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();
        
        $totalRevenue = $transactions->sum('total_amount');
        $totalDiscount = $transactions->sum('discount_amount');
        $totalTax = $transactions->sum('tax_amount');
        $netRevenue = $totalRevenue - $totalDiscount;
        
        // Cost of goods sold (COGS)
        $transactionItems = TransactionItem::whereHas('transaction', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', 'completed');
        })->with('product')->get();
        
        $totalCOGS = $transactionItems->sum(function ($item) {
            return $item->quantity * ($item->product->cost ?? 0);
        });
        
        $grossProfit = $netRevenue - $totalCOGS;
        $grossProfitMargin = $netRevenue > 0 ? ($grossProfit / $netRevenue) * 100 : 0;
        
        // Operating expenses
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->get();
        $expensesByCategory = $expenses->groupBy('category')->map(function ($group) {
            return $group->sum('amount');
        });
        
        $totalExpenses = $expenses->sum('amount');
        
        // Net profit calculation
        $netProfit = $grossProfit - $totalExpenses;
        $netProfitMargin = $netRevenue > 0 ? ($netProfit / $netRevenue) * 100 : 0;
        
        // Compare with previous period
        $periodLength = $startDate->diffInDays($endDate);
        $prevStartDate = $startDate->copy()->subDays($periodLength + 1);
        $prevEndDate = $startDate->copy()->subDay();
        
        $prevRevenue = Transaction::whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->where('status', 'completed')
            ->sum('total_amount');
        
        $prevExpenses = Expense::whereBetween('expense_date', [$prevStartDate, $prevEndDate])
            ->sum('amount');
        
        $revenueGrowth = $prevRevenue > 0 ? (($totalRevenue - $prevRevenue) / $prevRevenue) * 100 : 0;
        $expenseGrowth = $prevExpenses > 0 ? (($totalExpenses - $prevExpenses) / $prevExpenses) * 100 : 0;
        
        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'revenue' => [
                'gross_revenue' => $totalRevenue,
                'discounts' => $totalDiscount,
                'net_revenue' => $netRevenue,
                'tax_collected' => $totalTax
            ],
            'costs' => [
                'cost_of_goods_sold' => $totalCOGS,
                'operating_expenses' => $totalExpenses,
                'expenses_by_category' => $expensesByCategory
            ],
            'profit' => [
                'gross_profit' => $grossProfit,
                'gross_profit_margin' => $grossProfitMargin,
                'net_profit' => $netProfit,
                'net_profit_margin' => $netProfitMargin
            ],
            'comparison' => [
                'revenue_growth' => $revenueGrowth,
                'expense_growth' => $expenseGrowth,
                'previous_revenue' => $prevRevenue,
                'previous_expenses' => $prevExpenses
            ]
        ];
    }
    
    /**
     * Export report to PDF
     */
    public function exportToPDF($reportData, $reportType, $options = [])
    {
        $viewName = "reports.pdf.{$reportType}";
        $filename = $this->generatePDFFilename($reportType, $reportData);
        
        // Default PDF options
        $pdfOptions = array_merge([
            'format' => 'A4',
            'orientation' => 'portrait',
            'margin_top' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10
        ], $options);
        
        try {
            $pdf = Pdf::loadView($viewName, [
                'data' => $reportData,
                'company' => $this->getCompanyInfo(),
                'generated_at' => now()
            ]);
            
            // Set PDF options
            $pdf->setPaper($pdfOptions['format'], $pdfOptions['orientation']);
            $pdf->setOptions([
                'margin_top' => $pdfOptions['margin_top'],
                'margin_right' => $pdfOptions['margin_right'],
                'margin_bottom' => $pdfOptions['margin_bottom'],
                'margin_left' => $pdfOptions['margin_left'],
                'enable_php' => true,
                'enable_javascript' => false,
                'enable_remote' => false
            ]);
            
            return [
                'success' => true,
                'pdf' => $pdf,
                'filename' => $filename
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get top products for a period
     */
    private function getTopProductsForPeriod($startDate, $endDate, $limit = 10)
    {
        return TransactionItem::select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->whereHas('transaction', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
            })
            ->with(['product:id,name,image'])
            ->groupBy('product_id', 'product_name')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'stock' => $item->product ? $item->product->stock ?? 0 : 0,
                    'image' => $item->product ? $item->product->image : null,
                    'sold' => (int) $item->total_sold,
                    'revenue' => (float) $item->total_revenue
                ];
            });
    }
    
    /**
     * Get customer statistics for a period
     */
    private function getCustomerStatsForPeriod($startDate, $endDate)
    {
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();
        
        $totalCustomers = $transactions->where('customer_id', '!=', null)->unique('customer_id')->count();
        $walkInCustomers = $transactions->where('customer_id', null)->count();
        $returningCustomers = $transactions->where('customer_id', '!=', null)
            ->groupBy('customer_id')
            ->filter(function ($group) {
                return $group->count() > 1;
            })->count();
        
        $customerRetentionRate = $totalCustomers > 0 ? ($returningCustomers / $totalCustomers) * 100 : 0;
        
        return [
            'total_customers' => $totalCustomers,
            'walk_in_customers' => $walkInCustomers,
            'returning_customers' => $returningCustomers,
            'customer_retention_rate' => $customerRetentionRate
        ];
    }
    
    /**
     * Get stock status
     */
    private function getStockStatus($stock)
    {
        if ($stock == 0) return 'out_of_stock';
        if ($stock < 10) return 'low_stock';
        if ($stock < 50) return 'adequate';
        return 'high_stock';
    }
    
    /**
     * Generate PDF filename
     */
    private function generatePDFFilename($reportType, $reportData)
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        
        switch ($reportType) {
            case 'daily':
                $date = $reportData['date']->format('Y-m-d');
                return "daily_report_{$date}_{$timestamp}.pdf";
            case 'monthly':
                $period = $reportData['period'];
                return "monthly_report_{$period['year']}-{$period['month']}_{$timestamp}.pdf";
            case 'products':
                return "product_report_{$timestamp}.pdf";
            case 'stock':
                return "stock_report_{$timestamp}.pdf";
            case 'profit-loss':
                $start = $reportData['period']['start_date']->format('Y-m-d');
                $end = $reportData['period']['end_date']->format('Y-m-d');
                return "profit_loss_report_{$start}_to_{$end}_{$timestamp}.pdf";
            default:
                return "report_{$reportType}_{$timestamp}.pdf";
        }
    }
    
    /**
     * Get company information
     */
    private function getCompanyInfo()
    {
        return [
            'name' => config('app.name', 'CoffPOS'),
            'address' => 'Jl. Contoh No. 123, Jakarta 12345',
            'phone' => '021-12345678',
            'email' => 'info@coffpos.com',
            'website' => 'www.coffpos.com'
        ];
    }
}