@extends('reports.layouts.pdf')

@section('title', 'Monthly Sales Report')

@section('report-title', 'Monthly Sales Report')

@section('report-period', 'Period: ' . $data['period']['start_date']->format('F Y'))

@section('content')
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-title">Monthly Summary</div>
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
            <div class="summary-row">
                <div class="summary-cell label">Revenue Growth</div>
                <div class="summary-cell value text-bold {{ $data['summary']['revenue_growth'] >= 0 ? 'text-green' : 'text-red' }}">
                    {{ number_format($data['summary']['revenue_growth'], 2) }}%
                </div>
            </div>
        </div>
    </div>

    {{-- Daily Breakdown --}}
    @if($data['daily_breakdown']->count() > 0)
    <div class="section">
        <div class="section-title">Daily Sales Breakdown</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['daily_breakdown'] as $date => $stats)
                <tr>
                    <td class="text-bold">{{ \Carbon\Carbon::parse($date)->format('M j, Y') }}</td>
                    <td class="text-center">{{ number_format($stats['transactions']) }}</td>
                    <td class="text-right currency">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Weekly Breakdown --}}
    @if($data['weekly_breakdown']->count() > 0)
    <div class="section">
        <div class="section-title">Weekly Sales Breakdown</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Week</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['weekly_breakdown'] as $week => $stats)
                <tr>
                    <td class="text-bold">{{ $stats['week'] }}</td>
                    <td class="text-center">{{ number_format($stats['transactions']) }}</td>
                    <td class="text-right currency">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

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
                @foreach($data['top_products']->take(15) as $product)
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

    {{-- Customer Statistics --}}
    <div class="section">
        <div class="section-title">Customer Statistics</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell label">Total Customers</div>
                <div class="summary-cell value text-bold">
                    {{ number_format($data['customer_stats']['total_customers']) }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Walk-in Customers</div>
                <div class="summary-cell value">
                    {{ number_format($data['customer_stats']['walk_in_customers']) }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Returning Customers</div>
                <div class="summary-cell value">
                    {{ number_format($data['customer_stats']['returning_customers']) }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Customer Retention Rate</div>
                <div class="summary-cell value text-bold text-blue">
                    {{ number_format($data['customer_stats']['customer_retention_rate'], 2) }}%
                </div>
            </div>
        </div>
    </div>

    {{-- Expenses by Category --}}
    @if($data['expenses_by_category']->count() > 0)
    <div class="section">
        <div class="section-title">Expenses by Category</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-center">Count</th>
                    <th class="text-right">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['expenses_by_category'] as $category => $stats)
                <tr>
                    <td class="text-bold">{{ ucfirst($category) }}</td>
                    <td class="text-center">{{ number_format($stats['count']) }}</td>
                    <td class="text-right currency">Rp {{ number_format($stats['total'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection