<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $transaction->transaction_code }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .receipt {
            max-width: 300px;
            margin: 0 auto;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .store-info {
            font-size: 10px;
            margin-bottom: 2px;
        }
        .transaction-info {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .transaction-info div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .items {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 10px 0;
            margin-bottom: 10px;
        }
        .item {
            margin-bottom: 8px;
        }
        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }
        .totals {
            margin-bottom: 15px;
        }
        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .total-line.final {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 8px;
        }
        .payment-info {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-bottom: 15px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="store-name">CoffPOS</div>
            <div class="store-info">Jl. Coffee Street No. 123</div>
            <div class="store-info">Jakarta, Indonesia</div>
            <div class="store-info">Tel: (021) 1234-5678</div>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div>
                <span>Receipt #:</span>
                <span>{{ $transaction->transaction_code }}</span>
            </div>
            <div>
                <span>Date:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span>Cashier:</span>
                <span>{{ $transaction->user->name }}</span>
            </div>
            @if($transaction->customer)
            <div>
                <span>Customer:</span>
                <span>{{ $transaction->customer->name }}</span>
            </div>
            @endif
        </div>

        <!-- Items -->
        <div class="items">
            @foreach($transaction->transactionItems as $item)
            <div class="item">
                <div class="item-name">{{ $item->product->name }}</div>
                <div class="item-details">
                    <span>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-line">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($transaction->subtotal_amount, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount_amount > 0)
            <div class="total-line">
                <span>Discount:</span>
                <span>-Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($transaction->tax_amount > 0)
            <div class="total-line">
                <span>Tax (10%):</span>
                <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-line final">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="total-line">
                <span>Payment Method:</span>
                <span>{{ ucfirst($transaction->payment_method) }}</span>
            </div>
            @if($transaction->payment_method === 'cash')
            <div class="total-line">
                <span>Cash Received:</span>
                <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-line">
                <span>Change:</span>
                <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        @if($transaction->notes)
        <div class="payment-info">
            <div><strong>Notes:</strong></div>
            <div>{{ $transaction->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div>Thank you for your visit!</div>
            <div>Please come again</div>
            <div style="margin-top: 10px;">
                <div>Follow us:</div>
                <div>@coffpos_official</div>
            </div>
        </div>
    </div>

    <script>
        // Auto print if requested
        if (new URLSearchParams(window.location.search).get('auto_print') === '1') {
            window.onload = function() {
                window.print();
            };
        }
    </script>
</body>
</html>