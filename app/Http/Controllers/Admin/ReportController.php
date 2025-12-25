<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display reports menu
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Generate daily sales report
     */
    public function daily(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        
        try {
            $reportData = $this->reportService->generateDailyReport($date);
            
            if ($request->get('format') === 'pdf') {
                return $this->exportPDF($reportData, 'daily');
            }
            
            return view('admin.reports.daily', compact('reportData'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Failed to generate daily report: ' . $e->getMessage());
        }
    }

    /**
     * Generate monthly sales report
     */
    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        try {
            $reportData = $this->reportService->generateMonthlyReport($month, $year);
            
            if ($request->get('format') === 'pdf') {
                return $this->exportPDF($reportData, 'monthly');
            }
            
            return view('admin.reports.monthly', compact('reportData'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Failed to generate monthly report: ' . $e->getMessage());
        }
    }

    /**
     * Generate profit & loss report
     */
    public function profitLoss(Request $request)
    {
        // If no parameters provided, show the analysis dashboard
        if (!$request->hasAny(['start_date', 'end_date', 'format'])) {
            return view('admin.reports.profit-loss');
        }
        
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'format' => 'nullable|in:html,pdf'
        ]);
        
        $startDate = $validated['start_date'] ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? now()->endOfMonth()->format('Y-m-d');
        
        try {
            $reportData = $this->reportService->generateProfitLossReport($startDate, $endDate);
            
            if ($request->get('format') === 'pdf') {
                return $this->exportPDF($reportData, 'profit-loss');
            }
            
            return view('admin.reports.profit-loss', compact('reportData'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Failed to generate profit & loss report: ' . $e->getMessage());
        }
    }

    /**
     * Generate products report
     */
    public function products(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'format' => 'nullable|in:html,pdf'
        ]);
        
        $startDate = $validated['start_date'] ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? now()->endOfMonth()->format('Y-m-d');
        
        try {
            $reportData = $this->reportService->generateProductReport($startDate, $endDate);
            
            if ($request->get('format') === 'pdf') {
                return $this->exportPDF($reportData, 'products');
            }
            
            return view('admin.reports.products', compact('reportData'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Failed to generate products report: ' . $e->getMessage());
        }
    }

    /**
     * Generate stock report
     */
    public function stock(Request $request)
    {
        try {
            $reportData = $this->reportService->generateStockReport();
            
            if ($request->get('format') === 'pdf') {
                return $this->exportPDF($reportData, 'stock');
            }
            
            return view('admin.reports.stock', compact('reportData'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Failed to generate stock report: ' . $e->getMessage());
        }
    }

    /**
     * Generate customer report
     */
    public function customers(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'format' => 'nullable|in:html,pdf'
        ]);
        
        $startDate = $validated['start_date'] ?? now()->startOfMonth();
        $endDate = $validated['end_date'] ?? now()->endOfMonth();
        
        try {
            // Generate customer-specific report data
            $reportData = $this->generateCustomerReport($startDate, $endDate);
            
            if ($request->get('format') === 'pdf') {
                return $this->exportPDF($reportData, 'customers');
            }
            
            return view('admin.reports.customers', compact('reportData'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Failed to generate customer report: ' . $e->getMessage());
        }
    }

    /**
     * Generate expenses report
     */
    public function expenses(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'nullable|string',
            'format' => 'nullable|in:html,pdf'
        ]);
        
        $startDate = $validated['start_date'] ?? now()->startOfMonth();
        $endDate = $validated['end_date'] ?? now()->endOfMonth();
        $category = $validated['category'] ?? null;
        
        try {
            $reportData = $this->generateExpensesReport($startDate, $endDate, $category);
            
            if ($request->get('format') === 'pdf') {
                return $this->exportPDF($reportData, 'expenses');
            }
            
            return view('admin.reports.expenses', compact('reportData'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Failed to generate expenses report: ' . $e->getMessage());
        }
    }

    /**
     * Generate cashier performance report
     */
    public function cashierPerformance(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
            'format' => 'nullable|in:html,pdf'
        ]);
        
        $startDate = $validated['start_date'] ?? now()->startOfMonth();
        $endDate = $validated['end_date'] ?? now()->endOfMonth();
        $userId = $validated['user_id'] ?? null;
        
        try {
            $reportData = $this->generateCashierPerformanceReport($startDate, $endDate, $userId);
            
            if ($request->get('format') === 'pdf') {
                return $this->exportPDF($reportData, 'cashier-performance');
            }
            
            return view('admin.reports.cashier-performance', compact('reportData'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Failed to generate cashier performance report: ' . $e->getMessage());
        }
    }

    /**
     * Export report to PDF
     */
    public function exportPDF($reportData, $reportType)
    {
        try {
            $result = $this->reportService->exportToPDF($reportData, $reportType);
            
            if ($result['success']) {
                return $result['pdf']->download($result['filename']);
            } else {
                return back()->with('error', 'Failed to generate PDF: ' . $result['error']);
            }
            
        } catch (Exception $e) {
            return back()->with('error', 'Failed to export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate custom report
     */
    public function customReport(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:sales,products,customers,expenses,profit',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'group_by' => 'nullable|in:day,week,month',
            'filters' => 'nullable|array',
            'format' => 'nullable|in:html,pdf,csv'
        ]);
        
        try {
            $reportData = $this->generateCustomReportData($validated);
            
            switch ($validated['format']) {
                case 'pdf':
                    return $this->exportPDF($reportData, 'custom');
                case 'csv':
                    return $this->exportCSV($reportData, $validated['report_type']);
                default:
                    return view('admin.reports.custom', compact('reportData', 'validated'));
            }
            
        } catch (Exception $e) {
            return back()->with('error', 'Failed to generate custom report: ' . $e->getMessage());
        }
    }

    /**
     * Get report data for AJAX requests
     */
    public function getReportData(Request $request, $type)
    {
        try {
            switch ($type) {
                case 'daily':
                    $date = $request->get('date', today()->format('Y-m-d'));
                    $data = $this->reportService->generateDailyReport($date);
                    break;
                    
                case 'monthly':
                    $month = $request->get('month', now()->month);
                    $year = $request->get('year', now()->year);
                    $data = $this->reportService->generateMonthlyReport($month, $year);
                    break;
                    
                case 'products':
                    $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
                    $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
                    $data = $this->reportService->generateProductReport($startDate, $endDate);
                    break;
                    
                case 'stock':
                    $data = $this->reportService->generateStockReport();
                    break;
                    
                case 'profit-loss':
                    $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
                    $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
                    $data = $this->reportService->generateProfitLossReport($startDate, $endDate);
                    break;
                    
                default:
                    throw new Exception('Invalid report type');
            }
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Generate customer report data
     */
    private function generateCustomerReport($startDate, $endDate)
    {
        // Implementation for customer-specific reporting
        // This would include customer acquisition, retention, lifetime value, etc.
        return [
            'period' => ['start_date' => $startDate, 'end_date' => $endDate],
            'summary' => [],
            'customers' => []
        ];
    }

    /**
     * Generate expenses report data
     */
    private function generateExpensesReport($startDate, $endDate, $category = null)
    {
        // Implementation for expenses reporting
        return [
            'period' => ['start_date' => $startDate, 'end_date' => $endDate],
            'category' => $category,
            'summary' => [],
            'expenses' => []
        ];
    }

    /**
     * Generate cashier performance report data
     */
    private function generateCashierPerformanceReport($startDate, $endDate, $userId = null)
    {
        // Implementation for cashier performance reporting
        return [
            'period' => ['start_date' => $startDate, 'end_date' => $endDate],
            'user_id' => $userId,
            'summary' => [],
            'performance' => []
        ];
    }

    /**
     * Generate custom report data
     */
    private function generateCustomReportData($params)
    {
        // Implementation for custom report generation
        return [
            'type' => $params['report_type'],
            'period' => [
                'start_date' => $params['start_date'],
                'end_date' => $params['end_date']
            ],
            'group_by' => $params['group_by'] ?? 'day',
            'filters' => $params['filters'] ?? [],
            'data' => []
        ];
    }

    /**
     * Export data to CSV
     */
    private function exportCSV($reportData, $reportType)
    {
        $filename = "report_{$reportType}_" . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reportData, $reportType) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers based on report type
            switch ($reportType) {
                case 'sales':
                    fputcsv($file, ['Date', 'Transactions', 'Revenue', 'Items Sold']);
                    break;
                case 'products':
                    fputcsv($file, ['Product', 'Category', 'Quantity Sold', 'Revenue', 'Profit']);
                    break;
                // Add more cases as needed
            }
            
            // Add data rows
            // Implementation depends on report structure
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}