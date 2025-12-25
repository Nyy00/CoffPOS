@extends('reports.layouts.pdf')

@section('title', 'Stock Report')

@section('report-title', 'Stock Report')

@section('report-period', 'Generated on: ' . $data['generated_at']->format('F j, Y \a\t g:i A'))

@section('content')
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-title">Stock Summary</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell label">Total Products</div>
                <div class="summary-cell value text-bold">
                    {{ number_format($data['summary']['total_products']) }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Total Stock Value (Cost)</div>
                <div class="summary-cell value currency text-bold text-blue">
                    Rp {{ number_format($data['summary']['total_stock_value'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Total Retail Value</div>
                <div class="summary-cell value currency text-bold text-green">
                    Rp {{ number_format($data['summary']['total_retail_value'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Potential Profit</div>
                <div class="summary-cell value currency text-bold text-green">
                    Rp {{ number_format($data['summary']['potential_profit'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Out of Stock</div>
                <div class="summary-cell value text-bold text-red">
                    {{ number_format($data['summary']['out_of_stock_count']) }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Low Stock</div>
                <div class="summary-cell value text-bold text-red">
                    {{ number_format($data['summary']['low_stock_count']) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Out of Stock Products --}}
    @if($data['out_of_stock']->count() > 0)
    <div class="section">
        <div class="section-title">Out of Stock Products ({{ $data['out_of_stock']->count() }})</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Cost</th>
                    <th class="text-center">Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['out_of_stock'] as $item)
                <tr>
                    <td class="text-bold">{{ $item['product']->name }}</td>
                    <td>{{ $item['product']->category->name ?? 'N/A' }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['product']->cost, 0, ',', '.') }}</td>
                    <td class="text-center text-red text-bold">{{ $item['current_stock'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Low Stock Products --}}
    @if($data['low_stock']->count() > 0)
    <div class="section">
        <div class="section-title">Low Stock Products ({{ $data['low_stock']->count() }})</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Cost</th>
                    <th class="text-center">Stock</th>
                    <th class="text-right">Stock Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['low_stock'] as $item)
                <tr>
                    <td class="text-bold">{{ $item['product']->name }}</td>
                    <td>{{ $item['product']->category->name ?? 'N/A' }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['product']->cost, 0, ',', '.') }}</td>
                    <td class="text-center text-red text-bold">{{ $item['current_stock'] }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['stock_value'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Adequate Stock Products --}}
    @if($data['adequate_stock']->count() > 0)
    <div class="section">
        <div class="section-title">Adequate Stock Products ({{ $data['adequate_stock']->count() }})</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th class="text-right">Price</th>
                    <th class="text-center">Stock</th>
                    <th class="text-right">Stock Value</th>
                    <th class="text-right">Retail Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['adequate_stock']->take(20) as $item)
                <tr>
                    <td class="text-bold">{{ $item['product']->name }}</td>
                    <td>{{ $item['product']->category->name ?? 'N/A' }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</td>
                    <td class="text-center text-green">{{ $item['current_stock'] }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['stock_value'], 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['retail_value'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @if($data['adequate_stock']->count() > 20)
                <tr>
                    <td colspan="6" class="text-center" style="font-style: italic; color: #666;">
                        ... and {{ $data['adequate_stock']->count() - 20 }} more products with adequate stock
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    @endif

    {{-- High Stock Products --}}
    @if($data['high_stock']->count() > 0)
    <div class="section">
        <div class="section-title">High Stock Products ({{ $data['high_stock']->count() }})</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th class="text-right">Price</th>
                    <th class="text-center">Stock</th>
                    <th class="text-right">Stock Value</th>
                    <th class="text-right">Potential Profit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['high_stock']->take(15) as $item)
                <tr>
                    <td class="text-bold">{{ $item['product']->name }}</td>
                    <td>{{ $item['product']->category->name ?? 'N/A' }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</td>
                    <td class="text-center text-blue text-bold">{{ $item['current_stock'] }}</td>
                    <td class="text-right currency">Rp {{ number_format($item['stock_value'], 0, ',', '.') }}</td>
                    <td class="text-right currency text-green">Rp {{ number_format($item['potential_profit'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @if($data['high_stock']->count() > 15)
                <tr>
                    <td colspan="6" class="text-center" style="font-style: italic; color: #666;">
                        ... and {{ $data['high_stock']->count() - 15 }} more products with high stock
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    @endif

    {{-- Category Breakdown --}}
    @if($data['category_breakdown']->count() > 0)
    <div class="section">
        <div class="section-title">Stock by Category</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-center">Products</th>
                    <th class="text-center">Total Stock</th>
                    <th class="text-right">Total Value</th>
                    <th class="text-center">Out of Stock</th>
                    <th class="text-center">Low Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['category_breakdown'] as $category => $stats)
                <tr>
                    <td class="text-bold">{{ $category ?: 'Uncategorized' }}</td>
                    <td class="text-center">{{ number_format($stats['products_count']) }}</td>
                    <td class="text-center">{{ number_format($stats['total_stock']) }}</td>
                    <td class="text-right currency">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</td>
                    <td class="text-center {{ $stats['out_of_stock'] > 0 ? 'text-red text-bold' : '' }}">
                        {{ number_format($stats['out_of_stock']) }}
                    </td>
                    <td class="text-center {{ $stats['low_stock'] > 0 ? 'text-red text-bold' : '' }}">
                        {{ number_format($stats['low_stock']) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Stock Status Legend --}}
    <div class="section">
        <div class="section-title">Stock Status Legend</div>
        <div style="font-size: 11px; line-height: 1.6;">
            <p><span class="text-red text-bold">Out of Stock:</span> 0 items - Immediate restocking required</p>
            <p><span class="text-red text-bold">Low Stock:</span> 1-9 items - Restocking recommended</p>
            <p><span class="text-green text-bold">Adequate Stock:</span> 10-49 items - Normal stock level</p>
            <p><span class="text-blue text-bold">High Stock:</span> 50+ items - Consider reviewing demand</p>
        </div>
    </div>

    {{-- Recommendations --}}
    <div class="section">
        <div class="section-title">Recommendations</div>
        <div style="font-size: 11px; line-height: 1.6;">
            <p><strong>Priority Actions:</strong></p>
            <ul style="margin-left: 15px;">
                <li>Immediately restock {{ $data['summary']['out_of_stock_count'] }} out-of-stock products</li>
                <li>Plan restocking for {{ $data['summary']['low_stock_count'] }} low-stock products</li>
                <li>Review demand patterns for high-stock items to optimize inventory levels</li>
                <li>Consider implementing automatic reorder points for critical products</li>
            </ul>
        </div>
    </div>
@endsection