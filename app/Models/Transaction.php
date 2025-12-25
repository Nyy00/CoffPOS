<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'customer_id',
        'transaction_code',
        // Old column names for backward compatibility
        'subtotal',
        'discount',
        'tax',
        'total',
        // New column names
        'subtotal_amount',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'payment_method',
        'payment_amount',
        'change_amount',
        'status',
        'payment_status',
        'notes',
        'transaction_date',
        // Midtrans fields
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_snap_token',
        'midtrans_response',
    ];

    protected $casts = [
        // Old column names
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        // New column names
        'subtotal_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'transaction_date' => 'datetime',
        // Midtrans fields
        'midtrans_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    // Alias for backward compatibility
    public function items()
    {
        return $this->transactionItems();
    }
}
