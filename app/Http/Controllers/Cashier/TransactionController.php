<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display today's transactions for cashier
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'user'])
            ->where('user_id', auth()->id())
            ->whereDate('created_at', today());

        // Search by transaction code
        if ($request->filled('search')) {
            $query->where('transaction_code', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Today's summary for current cashier
        $todaySummary = [
            'total_transactions' => Transaction::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->count(),
            'total_revenue' => Transaction::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->sum('total_amount'),
            'cash_transactions' => Transaction::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->where('payment_method', 'cash')
                ->count(),
            'digital_transactions' => Transaction::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->whereIn('payment_method', ['debit', 'credit', 'e_wallet', 'qris'])
                ->count()
        ];

        return view('cashier.transactions.index', compact('transactions', 'todaySummary'));
    }

    /**
     * Show transaction details
     */
    public function show(Transaction $transaction)
    {
        // Ensure cashier can only view their own transactions
        if ($transaction->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to transaction');
        }

        $transaction->load(['customer', 'user', 'transactionItems.product']);

        return view('cashier.transactions.show', compact('transaction'));
    }

    /**
     * Reprint receipt
     */
    public function reprintReceipt(Transaction $transaction)
    {
        // Ensure cashier can only reprint their own transactions
        if ($transaction->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to transaction');
        }

        $transaction->load(['customer', 'user', 'transactionItems.product']);

        return view('receipts.transaction', compact('transaction'));
    }

    /**
     * Get today's summary for current cashier
     */
    public function todaySummary()
    {
        $summary = [
            'total_transactions' => Transaction::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->count(),
            'total_revenue' => Transaction::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->sum('total_amount'),
            'payment_methods' => Transaction::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
                ->groupBy('payment_method')
                ->get(),
            'hourly_sales' => Transaction::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) as hour, COUNT(*) as transactions, SUM(total_amount) as revenue')
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
        ];

        return response()->json($summary);
    }

    /**
     * Get recent transactions
     */
    public function getRecentTransactions()
    {
        $transactions = Transaction::with(['customer'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($transactions);
    }

    /**
     * Get hourly stats for today
     */
    public function getHourlyStats()
    {
        $stats = Transaction::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as transactions, SUM(total_amount) as revenue')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return response()->json($stats);
    }

    /**
     * Get shift summary for current cashier
     */
    public function shiftSummary(Request $request)
    {
        $startTime = $request->get('start_time', today()->format('Y-m-d 00:00:00'));
        $endTime = $request->get('end_time', now()->format('Y-m-d H:i:s'));

        $summary = [
            'shift_start' => $startTime,
            'shift_end' => $endTime,
            'total_transactions' => Transaction::where('user_id', auth()->id())
                ->whereBetween('created_at', [$startTime, $endTime])
                ->count(),
            'total_revenue' => Transaction::where('user_id', auth()->id())
                ->whereBetween('created_at', [$startTime, $endTime])
                ->sum('total_amount'),
            'payment_methods' => Transaction::where('user_id', auth()->id())
                ->whereBetween('created_at', [$startTime, $endTime])
                ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
                ->groupBy('payment_method')
                ->get(),
            'total_items_sold' => \DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->where('transactions.user_id', auth()->id())
                ->whereBetween('transactions.created_at', [$startTime, $endTime])
                ->sum('transaction_items.quantity'),
            'average_transaction' => Transaction::where('user_id', auth()->id())
                ->whereBetween('created_at', [$startTime, $endTime])
                ->avg('total_amount')
        ];

        return response()->json($summary);
    }
}