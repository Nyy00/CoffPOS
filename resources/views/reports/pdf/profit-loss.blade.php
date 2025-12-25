@extends('reports.layouts.pdf')

@section('title', 'Profit & Loss Report')

@section('report-title', 'Profit & Loss Statement')

@section('report-period', 'Period: ' . $data['period']['start_date']->format('M j, Y') . ' - ' . $data['period']['end_date']->format('M j, Y'))

@section('content')
    {{-- Revenue Section --}}
    <div class="summary-section">
        <div class="summary-title">Revenue</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell label">Gross Revenue</div>
                <div class="summary-cell value currency text-bold">
                    Rp {{ number_format($data['revenue']['gross_revenue'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Less: Discounts</div>
                <div class="summary-cell value currency text-red">
                    (Rp {{ number_format($data['revenue']['discounts'], 0, ',', '.') }})
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Net Revenue</div>
                <div class="summary-cell value currency text-bold text-green">
                    Rp {{ number_format($data['revenue']['net_revenue'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Tax Collected</div>
                <div class="summary-cell value currency text-blue">
                    Rp {{ number_format($data['revenue']['tax_collected'], 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Cost of Goods Sold --}}
    <div class="summary-section">
        <div class="summary-title">Cost of Goods Sold (COGS)</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell label">Total COGS</div>
                <div class="summary-cell value currency text-bold text-red">
                    Rp {{ number_format($data['costs']['cost_of_goods_sold'], 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Gross Profit --}}
    <div class="summary-section">
        <div class="summary-title">Gross Profit</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell label">Gross Profit</div>
                <div class="summary-cell value currency text-bold {{ $data['profit']['gross_profit'] >= 0 ? 'text-green' : 'text-red' }}">
                    Rp {{ number_format($data['profit']['gross_profit'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Gross Profit Margin</div>
                <div class="summary-cell value text-bold {{ $data['profit']['gross_profit_margin'] >= 30 ? 'text-green' : ($data['profit']['gross_profit_margin'] >= 15 ? 'text-blue' : 'text-red') }}">
                    {{ number_format($data['profit']['gross_profit_margin'], 2) }}%
                </div>
            </div>
        </div>
    </div>

    {{-- Operating Expenses --}}
    <div class="summary-section">
        <div class="summary-title">Operating Expenses</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell label">Total Operating Expenses</div>
                <div class="summary-cell value currency text-bold text-red">
                    Rp {{ number_format($data['costs']['operating_expenses'], 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Expenses by Category --}}
    @if($data['costs']['expenses_by_category']->count() > 0)
    <div class="section">
        <div class="section-title">Expenses Breakdown by Category</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-right">Amount</th>
                    <th class="text-center">% of Total Expenses</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalExpenses = $data['costs']['operating_expenses'];
                @endphp
                @foreach($data['costs']['expenses_by_category'] as $category => $amount)
                <tr>
                    <td class="text-bold">{{ ucfirst($category) }}</td>
                    <td class="text-right currency">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        {{ $totalExpenses > 0 ? number_format(($amount / $totalExpenses) * 100, 2) : 0 }}%
                    </td>
                </tr>
                @endforeach
                <tr style="background-color: #f3f4f6; font-weight: bold;">
                    <td>Total</td>
                    <td class="text-right currency">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
                    <td class="text-center">100%</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- Net Profit --}}
    <div class="summary-section">
        <div class="summary-title">Net Profit</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell label">Net Profit</div>
                <div class="summary-cell value currency text-bold {{ $data['profit']['net_profit'] >= 0 ? 'text-green' : 'text-red' }}">
                    Rp {{ number_format($data['profit']['net_profit'], 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Net Profit Margin</div>
                <div class="summary-cell value text-bold {{ $data['profit']['net_profit_margin'] >= 20 ? 'text-green' : ($data['profit']['net_profit_margin'] >= 10 ? 'text-blue' : 'text-red') }}">
                    {{ number_format($data['profit']['net_profit_margin'], 2) }}%
                </div>
            </div>
        </div>
    </div>

    {{-- Comparison with Previous Period --}}
    <div class="section">
        <div class="section-title">Period Comparison</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Metric</th>
                    <th class="text-right">Current Period</th>
                    <th class="text-right">Previous Period</th>
                    <th class="text-center">Growth</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-bold">Revenue</td>
                    <td class="text-right currency">Rp {{ number_format($data['revenue']['gross_revenue'], 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($data['comparison']['previous_revenue'], 0, ',', '.') }}</td>
                    <td class="text-center {{ $data['comparison']['revenue_growth'] >= 0 ? 'text-green' : 'text-red' }} text-bold">
                        {{ number_format($data['comparison']['revenue_growth'], 2) }}%
                    </td>
                </tr>
                <tr>
                    <td class="text-bold">Expenses</td>
                    <td class="text-right currency">Rp {{ number_format($data['costs']['operating_expenses'], 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($data['comparison']['previous_expenses'], 0, ',', '.') }}</td>
                    <td class="text-center {{ $data['comparison']['expense_growth'] <= 0 ? 'text-green' : 'text-red' }} text-bold">
                        {{ number_format($data['comparison']['expense_growth'], 2) }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Profit & Loss Summary --}}
    <div class="section">
        <div class="section-title">Profit & Loss Summary</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-bold">Net Revenue</td>
                    <td class="text-right currency text-green">Rp {{ number_format($data['revenue']['net_revenue'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Less: Cost of Goods Sold</td>
                    <td class="text-right currency text-red">(Rp {{ number_format($data['costs']['cost_of_goods_sold'], 0, ',', '.') }})</td>
                </tr>
                <tr style="background-color: #f9fafb;">
                    <td class="text-bold">Gross Profit</td>
                    <td class="text-right currency text-bold {{ $data['profit']['gross_profit'] >= 0 ? 'text-green' : 'text-red' }}">
                        Rp {{ number_format($data['profit']['gross_profit'], 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Less: Operating Expenses</td>
                    <td class="text-right currency text-red">(Rp {{ number_format($data['costs']['operating_expenses'], 0, ',', '.') }})</td>
                </tr>
                <tr style="background-color: #f3f4f6;">
                    <td class="text-bold" style="font-size: 13px;">Net Profit</td>
                    <td class="text-right currency text-bold {{ $data['profit']['net_profit'] >= 0 ? 'text-green' : 'text-red' }}" style="font-size: 13px;">
                        Rp {{ number_format($data['profit']['net_profit'], 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Key Performance Indicators --}}
    <div class="section">
        <div class="section-title">Key Performance Indicators</div>
        <div style="font-size: 11px; line-height: 1.8;">
            <p><strong>Gross Profit Margin:</strong> {{ number_format($data['profit']['gross_profit_margin'], 2) }}% 
                <span class="{{ $data['profit']['gross_profit_margin'] >= 30 ? 'text-green' : ($data['profit']['gross_profit_margin'] >= 15 ? 'text-blue' : 'text-red') }}">
                    ({{ $data['profit']['gross_profit_margin'] >= 30 ? 'Excellent' : ($data['profit']['gross_profit_margin'] >= 15 ? 'Good' : 'Needs Improvement') }})
                </span>
            </p>
            <p><strong>Net Profit Margin:</strong> {{ number_format($data['profit']['net_profit_margin'], 2) }}% 
                <span class="{{ $data['profit']['net_profit_margin'] >= 20 ? 'text-green' : ($data['profit']['net_profit_margin'] >= 10 ? 'text-blue' : 'text-red') }}">
                    ({{ $data['profit']['net_profit_margin'] >= 20 ? 'Excellent' : ($data['profit']['net_profit_margin'] >= 10 ? 'Good' : 'Needs Improvement') }})
                </span>
            </p>
            <p><strong>Revenue Growth:</strong> {{ number_format($data['comparison']['revenue_growth'], 2) }}% 
                <span class="{{ $data['comparison']['revenue_growth'] >= 0 ? 'text-green' : 'text-red' }}">
                    ({{ $data['comparison']['revenue_growth'] >= 0 ? 'Positive' : 'Negative' }})
                </span>
            </p>
            <p><strong>Expense Control:</strong> {{ number_format($data['comparison']['expense_growth'], 2) }}% 
                <span class="{{ $data['comparison']['expense_growth'] <= 0 ? 'text-green' : 'text-red' }}">
                    ({{ $data['comparison']['expense_growth'] <= 0 ? 'Controlled' : 'Increasing' }})
                </span>
            </p>
        </div>
    </div>

    {{-- Recommendations --}}
    <div class="section">
        <div class="section-title">Recommendations</div>
        <div style="font-size: 11px; line-height: 1.6;">
            @if($data['profit']['net_profit'] < 0)
                <p class="text-red"><strong>⚠ Action Required:</strong> Business is operating at a loss. Immediate cost reduction or revenue increase strategies needed.</p>
            @endif
            
            @if($data['profit']['gross_profit_margin'] < 15)
                <p><strong>Pricing Strategy:</strong> Consider reviewing product pricing or negotiating better supplier costs to improve gross margin.</p>
            @endif
            
            @if($data['comparison']['expense_growth'] > 10)
                <p><strong>Expense Management:</strong> Operating expenses are growing rapidly. Review and optimize operational costs.</p>
            @endif
            
            @if($data['comparison']['revenue_growth'] < 0)
                <p><strong>Revenue Growth:</strong> Revenue is declining. Focus on marketing, customer retention, and new product offerings.</p>
            @endif
            
            @if($data['profit']['net_profit'] > 0 && $data['profit']['net_profit_margin'] >= 15)
                <p><strong>Strong Performance:</strong> Business is profitable with healthy margins. Continue current strategies and explore growth opportunities.</p>
            @endif
        </div>
    </div>
@endsection