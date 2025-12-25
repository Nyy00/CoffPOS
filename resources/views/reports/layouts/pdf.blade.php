<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Report') - {{ $company['name'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 10px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .report-period {
            font-size: 12px;
            color: #666;
        }
        
        .summary-section {
            margin-bottom: 25px;
        }
        
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #1f2937;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        
        .summary-cell.label {
            background-color: #f9fafb;
            font-weight: bold;
            width: 40%;
        }
        
        .summary-cell.value {
            text-align: right;
            width: 60%;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .data-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #d1d5db;
            text-align: left;
            font-size: 11px;
        }
        
        .data-table td {
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-bold {
            font-weight: bold;
        }
        
        .text-green {
            color: #059669;
        }
        
        .text-red {
            color: #dc2626;
        }
        
        .text-blue {
            color: #2563eb;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #1f2937;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        
        .page-number:before {
            content: "Page " counter(page) " of " counter(pages);
        }
        
        .currency {
            font-family: 'DejaVu Sans Mono', monospace;
        }
        
        .highlight {
            background-color: #fef3c7;
            padding: 2px 4px;
            border-radius: 2px;
        }
        
        .chart-placeholder {
            width: 100%;
            height: 200px;
            border: 1px dashed #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-style: italic;
            margin: 15px 0;
        }
        
        @page {
            margin: 20mm;
        }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company['name'] }}</div>
        <div class="company-info">
            {{ $company['address'] }}<br>
            Phone: {{ $company['phone'] }} | Email: {{ $company['email'] }}
            @if(isset($company['website']))
                | Website: {{ $company['website'] }}
            @endif
        </div>
        <div class="report-title">@yield('report-title', 'Report')</div>
        <div class="report-period">@yield('report-period')</div>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <div class="footer">
        <div>Generated on {{ $generated_at->format('F j, Y \a\t g:i A') }}</div>
        <div class="page-number"></div>
    </div>

    @stack('scripts')
</body>
</html>