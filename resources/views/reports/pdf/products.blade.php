@extends('reports.layouts.pdf')

@section('title', 'Product Performance Report')

@section('report-title', 'Product Performance Report')

@section('report-period', 'Period: ' . $data['period']['start_date']->format('M j, Y') . ' - ' . $data['period']['end_date']->format('M j, Y'))

@section('content')
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-title">Product Summary</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell label">Total Products</div>
                <div class="summary-cell value text-bold">
                    {{ number_format($data['summary']['total_products']) }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Products with Sales</div>
                <div class="summary-cell value text-bold text-green">
                    {{ number_format($data['summary']['products_with_sales']) }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Products without Sales</div>
                <div class="summary-cell value text-bold text-red">
                    {{ number_format($data['summary']['products_without_sales']) }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Total Revenue</div>
                <div class="summary-cell value currency text-bold text-green">
                    Rp {{ number_format($data['summary']['total_revenue'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Total Profit</div>
                <div class="summary-cell value currency text-bold text-blue">
                    Rp {{ number_format($data['summary']['total_profit'], 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Top Performers --}}
    @if($data['top_performers']->count() > 0)
    <div class="section">
        <div class="section-title">Top Performing Products</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th class="text-center">Qty Sold</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-right">Profit</th>
                    <th class="text-center">Margin %</th>
                    <th class="text-center">Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['top_performers'] as $item)
                <tr>
                    <td class="text-bold">{{ $item['product']->name }}</td>
                    <td>{{ $item['product']->category->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($item['quantity_sold']) }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['revenue'], 0, ',', '.') }}</td>
                    <td class="text-right currency {{ $item['profit'] >= 0 ? 'text-green' : 'text-red' }}">
                        Rp {{ number_format($item['profit'], 0, ',', '.') }}
                    </td>
                    <td class="text-center {{ $item['profit_margin'] >= 20 ? 'text-green' : ($item['profit_margin'] >= 10 ? 'text-blue' : 'text-red') }}">
                        {{ number_format($item['profit_margin'], 1) }}%
                    </td>
                    <td class="text-center">
                        <span class="{{ $item['stock_status'] === 'out_of_stock' ? 'text-red' : ($item['stock_status'] === 'low_stock' ? 'text-red' : 'text-green') }}">
                            {{ number_format($item['current_stock']) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Category Performance --}}
    @if($data['category_stats']->count() > 0)
    <div class="section">
        <div class="section-title">Category Performance</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-center">Products</th>
                    <th class="text-center">Qty Sold</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['category_stats'] as $category => $stats)
                <tr>
                    <td class="text-bold">{{ $category ?: 'Uncategorized' }}</td>
                    <td class="text-center">{{ number_format($stats['products_count']) }}</td>
                    <td class="text-center">{{ number_format($stats['quantity_sold']) }}</td>
                    <td class="text-right currency">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Low Performers --}}
    @if($data['low_performers']->count() > 0)
    <div class="section">
        <div class="section-title">Low Performing Products</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th class="text-center">Qty Sold</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-center">Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['low_performers'] as $item)
                <tr>
                    <td class="text-bold">{{ $item['product']->name }}</td>
                    <td>{{ $item['product']->category->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($item['quantity_sold']) }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['revenue'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item['current_stock']) }}</td>
                    <td>
                        <span class="{{ $item['stock_status'] === 'out_of_stock' ? 'text-red' : ($item['stock_status'] === 'low_stock' ? 'text-red' : 'text-green') }}">
                            {{ ucfirst(str_replace('_', ' ', $item['stock_status'])) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Products with No Sales --}}
    @if($data['no_sales']->count() > 0)
    <div class="section">
        <div class="section-title">Products with No Sales</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th class="text-right">Price</th>
                    <th class="text-center">Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['no_sales']->take(20) as $item)
                <tr>
                    <td class="text-bold">{{ $item['product']->name }}</td>
                    <td>{{ $item['product']->category->name ?? 'N/A' }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item['current_stock']) }}</td>
                    <td>
                        <span class="{{ $item['stock_status'] === 'out_of_stock' ? 'text-red' : ($item['stock_status'] === 'low_stock' ? 'text-red' : 'text-green') }}">
                            {{ ucfirst(str_replace('_', ' ', $item['stock_status'])) }}
                        </span>
                    </td>
                </tr>
                @endforeach
                @if($data['no_sales']->count() > 20)
                <tr>
                    <td colspan="5" class="text-center" style="font-style: italic; color: #666;">
                        ... and {{ $data['no_sales']->count() - 20 }} more products
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    @endif

    {{-- Recommendations --}}
    <div class="section">
        <div class="section-title">Recommendations</div>
        <div style="font-size: 11px; line-height: 1.6;">
            <p><strong>High Performers:</strong> Consider increasing stock levels for top-selling products to avoid stockouts.</p>
            <p><strong>Low Performers:</strong> Review pricing strategy or consider promotions for slow-moving items.</p>
            <p><strong>No Sales:</strong> Evaluate whether to discontinue products with zero sales or implement marketing campaigns.</p>
            <p><strong>Stock Management:</strong> Focus on maintaining optimal stock levels for high-margin products.</p>
        </div>
    </div>
@endsection