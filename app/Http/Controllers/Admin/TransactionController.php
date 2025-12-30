<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'user', 'transactionItems.product'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->get('payment_method'));
        }

        if ($request->filled('cashier_id')) {
            $query->where('user_id', $request->get('cashier_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        if ($request->filled('amount_min')) {
            $query->where('total_amount', '>=', $request->get('amount_min'));
        }

        if ($request->filled('amount_max')) {
            $query->where('total_amount', '<=', $request->get('amount_max'));
        }

        $transactions = $query->paginate(20);

        // Get filter options
        $cashiers = User::where('role', 'cashier')->get();
        $paymentMethods = Transaction::distinct()->pluck('payment_method')->filter();
        $statuses = Transaction::distinct()->pluck('status')->filter();

        return view('admin.transactions.index', compact(
            'transactions', 
            'cashiers', 
            'paymentMethods', 
            'statuses'
        ));
    }

    /**
     * Display the specified transaction
     */
    public function show(Transaction $transaction)
    {
        $transaction->load([
            'customer', 
            'user', 
            'transactionItems.product.category'
        ]);

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Void a transaction
     */
    public function void(Request $request, Transaction $transaction)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Check if transaction can be voided (within 24 hours and completed)
            if ($transaction->status !== 'completed') {
                return back()->with('error', 'Only completed transactions can be voided.');
            }

            if ($transaction->created_at->diffInHours(now()) > 24) {
                return back()->with('error', 'Transactions older than 24 hours cannot be voided.');
            }

            // Restore stock for each item
            foreach ($transaction->transactionItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            // Update customer points if applicable
            if ($transaction->customer && $transaction->points_earned > 0) {
                $transaction->customer->decrement('loyalty_points', $transaction->points_earned);
            }

            // Update transaction status
            $transaction->update([
                'status' => 'voided',
                'void_reason' => $request->reason,
                'voided_by' => auth()->id(),
                'voided_at' => now()
            ]);

            DB::commit();

            return back()->with('success', 'Transaction has been voided successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to void transaction: ' . $e->getMessage());
        }
    }

    /**
     * Export transactions to CSV
     */
    public function export(Request $request)
    {
        $query = Transaction::with(['customer', 'user', 'transactionItems.product']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->get('payment_method'));
        }

        if ($request->filled('cashier_id')) {
            $query->where('user_id', $request->get('cashier_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $transactions = $query->get();

        $filename = 'transactions_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Transaction Code',
                'Date',
                'Time',
                'Customer',
                'Cashier',
                'Items Count',
                'Subtotal',
                'Discount',
                'Tax',
                'Total Amount',
                'Payment Method',
                'Status',
                'Points Earned'
            ]);

            // Data rows
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_code,
                    $transaction->created_at->format('Y-m-d'),
                    $transaction->created_at->format('H:i:s'),
                    $transaction->customer ? $transaction->customer->name : 'Walk-in',
                    $transaction->user->name,
                    $transaction->transactionItems->count(),
                    $transaction->subtotal,
                    $transaction->discount_amount,
                    $transaction->tax_amount,
                    $transaction->total_amount,
                    ucfirst(str_replace('_', ' ', $transaction->payment_method)),
                    ucfirst($transaction->status),
                    $transaction->points_earned ?? 0
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Search transactions API
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $transactions = Transaction::with(['customer', 'user'])
            ->where('transaction_code', 'like', "%{$query}%")
            ->orWhereHas('customer', function ($customerQuery) use ($query) {
                $customerQuery->where('name', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'customer_name' => $transaction->customer ? $transaction->customer->name : 'Walk-in',
                    'cashier_name' => $transaction->user->name,
                    'total_amount' => $transaction->total_amount,
                    'status' => $transaction->status,
                    'created_at' => $transaction->created_at->format('M j, Y H:i'),
                    'url' => route('admin.transactions.show', $transaction)
                ];
            })
        ]);
    }

    /**
     * Filter transactions API
     */
    public function filter(Request $request)
    {
        $query = Transaction::with(['customer', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->get('payment_method'));
        }

        if ($request->filled('cashier_id')) {
            $query->where('user_id', $request->get('cashier_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        if ($request->filled('amount_min')) {
            $query->where('total_amount', '>=', $request->get('amount_min'));
        }

        if ($request->filled('amount_max')) {
            $query->where('total_amount', '<=', $request->get('amount_max'));
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Get transaction statistics
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'today');
        
        $query = Transaction::where('status', 'completed');

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        $stats = [
            'total_transactions' => $query->count(),
            'total_revenue' => $query->sum('total_amount'),
            'average_transaction' => $query->avg('total_amount'),
            'total_items_sold' => $query->withSum('transactionItems', 'quantity')->get()->sum('transaction_items_sum_quantity')
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Reprint receipt
     */
    public function reprintReceipt(Transaction $transaction)
    {
        $transaction->load([
            'customer', 
            'user', 
            'transactionItems.product'
        ]);

        return view('receipts.transaction', compact('transaction'));
    }

    /**
     * Get daily summary
     */
    public function getDailySummary(Request $request)
    {
        $date = $request->get('date', today());
        
        $transactions = Transaction::with(['transactionItems'])
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->get();

        $summary = [
            'date' => Carbon::parse($date)->format('Y-m-d'),
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->sum('total_amount'),
            'total_items' => $transactions->sum(function ($transaction) {
                return $transaction->transactionItems->sum('quantity');
            }),
            'average_transaction' => $transactions->count() > 0 ? $transactions->avg('total_amount') : 0,
            'payment_methods' => $transactions->groupBy('payment_method')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_amount')
                ];
            }),
            'hourly_breakdown' => $transactions->groupBy(function ($transaction) {
                return $transaction->created_at->format('H');
            })->map(function ($group) {
                return [
                    'transactions' => $group->count(),
                    'revenue' => $group->sum('total_amount')
                ];
            })->sortKeys()
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Get payment method statistics
     */
    public function getPaymentMethodStats(Request $request)
    {
        $period = $request->get('period', 'month');
        
        $query = Transaction::where('status', 'completed');

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
        }

        $stats = $query->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => ucfirst(str_replace('_', ' ', $item->payment_method)),
                    'count' => $item->count,
                    'total' => $item->total,
                    'percentage' => 0 // Will be calculated in frontend
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Bulk void transactions
     */
    public function bulkVoid(Request $request)
    {
        $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id',
            'reason' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $transactions = Transaction::whereIn('id', $request->transaction_ids)
                ->where('status', 'completed')
                ->get();

            $voidedCount = 0;

            foreach ($transactions as $transaction) {
                // Check if transaction can be voided (within 24 hours)
                if ($transaction->created_at->diffInHours(now()) <= 24) {
                    // Restore stock
                    foreach ($transaction->transactionItems as $item) {
                        if ($item->product) {
                            $item->product->increment('stock', $item->quantity);
                        }
                    }

                    // Update customer points
                    if ($transaction->customer && $transaction->points_earned > 0) {
                        $transaction->customer->decrement('loyalty_points', $transaction->points_earned);
                    }

                    // Void transaction
                    $transaction->update([
                        'status' => 'voided',
                        'void_reason' => $request->reason,
                        'voided_by' => auth()->id(),
                        'voided_at' => now()
                    ]);

                    $voidedCount++;
                }
            }

            DB::commit();

            return back()->with('success', "Successfully voided {$voidedCount} transactions.");

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to void transactions: ' . $e->getMessage());
        }
    }
}