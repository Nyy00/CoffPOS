@extends('reports.layouts.pdf')

@section('title', 'Daily Sales Report')

@section('report-title', 'Daily Sales Report')

@section('report-period', 'Date: ' . $data['date']->format('F j, Y'))

@section('content')
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-title">Daily Summary</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell label">Total Revenue</div>
                <div class="summary-cell value currency text-bold text-green">
                    Rp {{ number_format($data['summary']['total_revenue'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Total Transactions</div>
                <div class="summary-cell value text-bold">
                    {{ number_format($data['summary']['total_transactions']) }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Total Items Sold</div>
                <div class="summary-cell value text-bold">
                    {{ number_format($data['summary']['total_items']) }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Average Transaction</div>
                <div class="summary-cell value currency">
                    Rp {{ number_format($data['summary']['average_transaction'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Total Expenses</div>
                <div class="summary-cell value currency text-red">
                    Rp {{ number_format($data['summary']['total_expenses'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Net Profit</div>
                <div class="summary-cell value currency text-bold {{ $data['summary']['net_profit'] >= 0 ? 'text-green' : 'text-red' }}">
                    Rp {{ number_format($data['summary']['net_profit'], 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Methods Breakdown --}}
    @if($data['payment_methods']->count() > 0)
    <div class="section">
        <div class="section-title">Payment Methods Breakdown</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-right">Amount</th>
                    <th class="text-center">Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['payment_methods'] as $method => $stats)
                <tr>
                    <td class="text-bold">{{ ucfirst(str_replace('_', ' ', $method)) }}</td>
                    <td class="text-center">{{ number_format($stats['count']) }}</td>
                    <td class="text-right currency">Rp {{ number_format($stats['total'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $stats['percentage'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Hourly Breakdown --}}
    @if($data['hourly_breakdown']->count() > 0)
    <div class="section">
        <div class="section-title">Hourly Sales Breakdown</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Hour</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['hourly_breakdown'] as $hour => $stats)
                <tr>
                    <td class="text-bold">{{ sprintf('%02d:00 - %02d:59', $hour, $hour) }}</td>
                    <td class="text-center">{{ number_format($stats['transactions']) }}</td>
                    <td class="text-right currency">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Top Products --}}
    @if($data['top_products']->count() > 0)
    <div class="section">
        <div class="section-title">Top Selling Products</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th class="text-center">Quantity Sold</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['top_products']->take(10) as $product)
                <tr>
                    <td class="text-bold">{{ $product->product_name }}</td>
                    <td class="text-center">{{ number_format($product->total_sold) }}</td>
                    <td class="text-right currency">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Top Customers --}}
    @if($data['top_customers']->count() > 0)
    <div class="section">
        <div class="section-title">Top Customers</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-right">Total Spent</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['top_customers']->take(10) as $customer)
                <tr>
                    <td class="text-bold">{{ $customer['customer']->name }}</td>
                    <td class="text-center">{{ number_format($customer['transactions']) }}</td>
                    <td class="text-right currency">Rp {{ number_format($customer['total_spent'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Expenses --}}
    @if($data['expenses']->count() > 0)
    <div class="section">
        <div class="section-title">Daily Expenses</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Category</th>
                    <th class="text-right">Amount</th>
                    <th>Recorded By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['expenses'] as $expense)
                <tr>
                    <td>{{ $expense->description }}</td>
                    <td>{{ ucfirst($expense->category) }}</td>
                    <td class="text-right currency">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td>{{ $expense->user->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Transactions Detail --}}
    @if($data['transactions']->count() > 0)
    <div class="section">
        <div class="section-title">Transaction Details</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Transaction Code</th>
                    <th>Customer</th>
                    <th>Cashier</th>
                    <th class="text-center">Items</th>
                    <th class="text-right">Total</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['transactions'] as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('H:i') }}</td>
                    <td class="text-bold">{{ $transaction->transaction_code }}</td>
                    <td>{{ $transaction->customer ? $transaction->customer->name : 'Walk-in' }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td class="text-center">{{ $transaction->transactionItems->sum('quantity') }}</td>
                    <td class="text-right currency">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection