<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class TransactionService
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    /**
     * Create a new transaction
     */
    public function createTransaction(array $data)
    {
        // Calculate totals
        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discountAmount = $data['discount_amount'] ?? 0;
        $taxAmount = $data['tax_amount'] ?? 0;
        $totalAmount = $subtotal - $discountAmount + $taxAmount;

        // Generate transaction code
        $transactionCode = $this->generateTransactionCode();

        // Create transaction with constraint error handling
        try {
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'customer_id' => $data['customer_id'],
                'user_id' => $data['user_id'],
                // Old columns for backward compatibility
                'subtotal' => $subtotal,
                'discount' => $discountAmount,
                'tax' => $taxAmount,
                'total' => $totalAmount,
                // New columns
                'subtotal_amount' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $data['payment_method'],
                'payment_amount' => $data['payment_amount'],
                'change_amount' => $data['payment_amount'] - $totalAmount,
                'transaction_date' => Carbon::now(),
                'notes' => $data['notes'] ?? null,
                'status' => 'completed'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle payment method constraint violation
            if (strpos($e->getMessage(), 'transactions_payment_method_check') !== false) {
                // Log the error
                Log::error('Payment method constraint violation', [
                    'payment_method' => $data['payment_method'],
                    'error' => $e->getMessage()
                ]);
                
                // Fallback: map digital to a supported payment method
                $fallbackMethod = $data['payment_method'] === 'digital' ? 'ewallet' : 'cash';
                
                Log::info('Using fallback payment method', [
                    'original' => $data['payment_method'],
                    'fallback' => $fallbackMethod
                ]);
                
                // Retry with fallback method
                $transaction = Transaction::create([
                    'transaction_code' => $transactionCode,
                    'customer_id' => $data['customer_id'],
                    'user_id' => $data['user_id'],
                    // Old columns for backward compatibility
                    'subtotal' => $subtotal,
                    'discount' => $discountAmount,
                    'tax' => $taxAmount,
                    'total' => $totalAmount,
                    // New columns
                    'subtotal_amount' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'payment_method' => $fallbackMethod,
                    'payment_amount' => $data['payment_amount'],
                    'change_amount' => $data['payment_amount'] - $totalAmount,
                    'transaction_date' => Carbon::now(),
                    'notes' => ($data['notes'] ?? '') . " [Original payment method: {$data['payment_method']}]",
                    'status' => 'completed'
                ]);
            } else {
                // Re-throw other database errors
                throw $e;
            }
        }

        // Create transaction items and update stock
        foreach ($data['items'] as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);

            // Update product stock
            $this->updateStock($item['product_id'], $item['quantity']);
        }

        // Apply loyalty points if customer exists
        if ($data['customer_id']) {
            $this->applyLoyaltyPoints($data['customer_id'], $totalAmount);
        }

        return $transaction->load(['customer', 'user', 'transactionItems.product']);
    }

    /**
     * Generate unique transaction code
     */
    public function generateTransactionCode()
    {
        $date = Carbon::now()->format('Ymd');
        $lastTransaction = Transaction::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastTransaction ? (int) substr($lastTransaction->transaction_code, -4) + 1 : 1;
        
        return 'TRX-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Update product stock
     */
    public function updateStock($productId, $quantity)
    {
        $product = Product::findOrFail($productId);
        $product->decrement('stock', $quantity);
        
        return $product;
    }

    /**
     * Apply loyalty points to customer
     */
    public function applyLoyaltyPoints($customerId, $totalAmount)
    {
        $customer = Customer::findOrFail($customerId);
        
        // Calculate points (1 point per 10,000 IDR)
        $pointsEarned = floor($totalAmount / 10000);
        
        $customer->increment('points', $pointsEarned);
        
        return $customer;
    }

    /**
     * Void a transaction
     */
    public function voidTransaction($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->status === 'voided') {
            throw new \Exception('Transaction is already voided');
        }

        DB::beginTransaction();
        try {
            // Restore product stock
            foreach ($transaction->transactionItems as $item) {
                $product = Product::findOrFail($item->product_id);
                $product->increment('stock', $item->quantity);
            }

            // Deduct loyalty points if customer exists
            if ($transaction->customer_id) {
                $pointsToDeduct = floor($transaction->total_amount / 10000);
                $customer = Customer::findOrFail($transaction->customer_id);
                $customer->decrement('points', $pointsToDeduct);
            }

            // Update transaction status
            $transaction->update(['status' => 'voided']);

            DB::commit();
            return $transaction;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Calculate total from items
     */
    public function calculateTotal(array $items, $discount = 0, $tax = 0)
    {
        $subtotal = 0;
        
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $total = $subtotal - $discount + $tax;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total
        ];
    }

    /**
     * Process POS transaction with validation
     */
    public function processPOSTransaction(array $data)
    {
        DB::beginTransaction();
        
        try {
            // Validate cart items
            $this->validateCartItems($data['items']);
            
            // Check stock availability
            $this->checkStockAvailability($data['items']);
            
            // Calculate totals with validation
            $totals = $this->calculateTransactionTotals($data);
            
            // Apply customer loyalty discount if applicable
            if (isset($data['customer_id']) && isset($data['use_loyalty_points']) && $data['use_loyalty_points']) {
                $totals = $this->applyLoyaltyDiscount($data['customer_id'], $totals, $data['loyalty_points_used'] ?? 0);
            }
            
            // Create transaction
            $transaction = $this->createPOSTransaction($data, $totals);
            
            // Process payment
            $this->processPayment($transaction, $data['payment']);
            
            // Update inventory
            $this->updateInventory($data['items']);
            
            // Update customer loyalty points
            if ($transaction->customer_id) {
                $this->updateCustomerLoyalty($transaction);
            }
            
            // Log transaction
            $this->logTransaction($transaction, 'created');
            
            DB::commit();
            
            return $transaction->load(['customer', 'user', 'transactionItems.product']);
            
        } catch (Exception $e) {
            DB::rollback();
            Log::error('POS Transaction failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Validate cart items
     */
    private function validateCartItems(array $items)
    {
        if (empty($items)) {
            throw new Exception('Cart is empty');
        }

        foreach ($items as $item) {
            if (!isset($item['product_id']) || !isset($item['quantity']) || !isset($item['price'])) {
                throw new Exception('Invalid item data');
            }

            if ($item['quantity'] <= 0) {
                throw new Exception('Invalid quantity for item');
            }

            if ($item['price'] <= 0) {
                throw new Exception('Invalid price for item');
            }
        }
    }

    /**
     * Check stock availability for all items
     */
    private function checkStockAvailability(array $items)
    {
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            
            if (!$product) {
                throw new Exception("Product not found: {$item['product_id']}");
            }

            if (!$product->is_available) {
                throw new Exception("Product is not available: {$product->name}");
            }

            if ($product->stock < $item['quantity']) {
                throw new Exception("Insufficient stock for {$product->name}. Available: {$product->stock}, Requested: {$item['quantity']}");
            }
        }
    }

    /**
     * Calculate transaction totals with tax and discount
     */
    private function calculateTransactionTotals(array $data)
    {
        $subtotal = 0;
        
        foreach ($data['items'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discountAmount = $data['discount_amount'] ?? 0;
        $discountPercent = $data['discount_percent'] ?? 0;
        
        // Apply percentage discount
        if ($discountPercent > 0) {
            $discountAmount += ($subtotal * $discountPercent / 100);
        }

        // Calculate tax (default 10% PPN in Indonesia)
        $taxPercent = $data['tax_percent'] ?? 10;
        $taxAmount = ($subtotal - $discountAmount) * $taxPercent / 100;

        $totalAmount = $subtotal - $discountAmount + $taxAmount;

        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'discount_percent' => $discountPercent,
            'tax_amount' => $taxAmount,
            'tax_percent' => $taxPercent,
            'total_amount' => $totalAmount
        ];
    }

    /**
     * Apply loyalty points discount
     */
    private function applyLoyaltyDiscount($customerId, array $totals, $pointsToUse)
    {
        $customer = Customer::find($customerId);
        
        if (!$customer) {
            throw new Exception('Customer not found');
        }

        if ($customer->points < $pointsToUse) {
            throw new Exception('Insufficient loyalty points');
        }

        // 1 point = 1 IDR discount, max 50% of total
        $maxDiscount = $totals['total_amount'] * 0.5;
        $loyaltyDiscount = min($pointsToUse, $maxDiscount);

        $totals['loyalty_discount'] = $loyaltyDiscount;
        $totals['loyalty_points_used'] = $loyaltyDiscount; // 1:1 ratio
        $totals['total_amount'] -= $loyaltyDiscount;

        return $totals;
    }

    /**
     * Create POS transaction record
     */
    private function createPOSTransaction(array $data, array $totals)
    {
        $transactionCode = $this->generateTransactionCode();

        try {
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'customer_id' => $data['customer_id'] ?? null,
                'user_id' => auth()->id(),
                // Old column names for backward compatibility
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount_amount'],
                'tax' => $totals['tax_amount'],
                'total' => $totals['total_amount'],
                // New column names
                'subtotal_amount' => $totals['subtotal'],
                'discount_amount' => $totals['discount_amount'],
                'tax_amount' => $totals['tax_amount'],
                'total_amount' => $totals['total_amount'],
                'payment_method' => $data['payment']['method'],
                'payment_amount' => $data['payment']['amount'],
                'change_amount' => $data['payment']['amount'] - $totals['total_amount'],
                'transaction_date' => now(),
                'notes' => $data['notes'] ?? null,
                'status' => 'completed'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle payment method constraint violation
            if (strpos($e->getMessage(), 'transactions_payment_method_check') !== false) {
                // Log the error
                Log::error('Payment method constraint violation in POS transaction', [
                    'payment_method' => $data['payment']['method'],
                    'error' => $e->getMessage()
                ]);
                
                // Fallback: map digital to a supported payment method
                $fallbackMethod = $data['payment']['method'] === 'digital' ? 'ewallet' : 'cash';
                
                Log::info('Using fallback payment method for POS transaction', [
                    'original' => $data['payment']['method'],
                    'fallback' => $fallbackMethod
                ]);
                
                // Retry with fallback method
                $transaction = Transaction::create([
                    'transaction_code' => $transactionCode,
                    'customer_id' => $data['customer_id'] ?? null,
                    'user_id' => auth()->id(),
                    // Old column names for backward compatibility
                    'subtotal' => $totals['subtotal'],
                    'discount' => $totals['discount_amount'],
                    'tax' => $totals['tax_amount'],
                    'total' => $totals['total_amount'],
                    // New column names
                    'subtotal_amount' => $totals['subtotal'],
                    'discount_amount' => $totals['discount_amount'],
                    'tax_amount' => $totals['tax_amount'],
                    'total_amount' => $totals['total_amount'],
                    'payment_method' => $fallbackMethod,
                    'payment_amount' => $data['payment']['amount'],
                    'change_amount' => $data['payment']['amount'] - $totals['total_amount'],
                    'transaction_date' => now(),
                    'notes' => ($data['notes'] ?? '') . " [Original payment method: {$data['payment']['method']}]",
                    'status' => 'completed'
                ]);
            } else {
                // Re-throw other database errors
                throw $e;
            }
        }

        // Create transaction items
        foreach ($data['items'] as $item) {
            $product = Product::find($item['product_id']);
            
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'product_name' => $product->name,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
                'notes' => $item['notes'] ?? null
            ]);
        }

        return $transaction;
    }

    /**
     * Process payment validation
     */
    private function processPayment(Transaction $transaction, array $payment)
    {
        $paymentAmount = $payment['amount'];
        $totalAmount = $transaction->total_amount;

        // Validate payment method first
        $allowedMethods = ['cash', 'debit', 'credit', 'digital', 'e-wallet', 'qris'];
        if (!in_array($payment['method'], $allowedMethods)) {
            throw new Exception('Invalid payment method');
        }

        // For digital payments, Midtrans handles the amount validation
        if ($payment['method'] === 'digital') {
            if (empty($payment['reference'])) {
                throw new Exception('Payment reference required for digital payments');
            }
            return true;
        }

        // For other payment methods, check amount
        if ($paymentAmount < $totalAmount) {
            throw new Exception('Insufficient payment amount');
        }

        // For non-cash payments, validate additional data
        if ($payment['method'] !== 'cash') {
            if (empty($payment['reference'])) {
                throw new Exception('Payment reference required for non-cash payments');
            }
        }

        return true;
    }

    /**
     * Update inventory for all items
     */
    private function updateInventory(array $items)
    {
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            $product->decrement('stock', $item['quantity']);
            
            // Log stock movement
            Log::info('Stock updated', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity_sold' => $item['quantity'],
                'remaining_stock' => $product->fresh()->stock
            ]);
        }
    }

    /**
     * Update customer loyalty points
     */
    private function updateCustomerLoyalty(Transaction $transaction)
    {
        $customer = Customer::find($transaction->customer_id);
        
        // Deduct used loyalty points
        if (isset($transaction->loyalty_points_used)) {
            $customer->decrement('points', $transaction->loyalty_points_used);
        }
        
        // Add new points (1 point per 10,000 IDR)
        $pointsEarned = floor($transaction->total_amount / 10000);
        $customer->increment('points', $pointsEarned);
        
        Log::info('Customer loyalty updated', [
            'customer_id' => $customer->id,
            'points_used' => $transaction->loyalty_points_used ?? 0,
            'points_earned' => $pointsEarned,
            'total_points' => $customer->fresh()->points
        ]);
    }

    /**
     * Hold transaction for later processing
     */
    public function holdTransaction(array $data)
    {
        $holdData = [
            'items' => $data['items'],
            'customer_name' => $data['customer_name'] ?? null,
            'discount_amount' => $data['discount_amount'] ?? 0,
            'notes' => $data['notes'] ?? null,
            'reason' => $data['reason'],
            'held_at' => now(),
            'held_by' => auth()->user()->name ?? 'Unknown'
        ];

        // Store in session or cache
        $holdId = 'hold_' . uniqid();
        session()->put("transaction_hold.{$holdId}", $holdData);

        return $holdId;
    }

    /**
     * Retrieve held transaction
     */
    public function getHeldTransaction($holdId)
    {
        return session()->get("transaction_hold.{$holdId}");
    }

    /**
     * Release held transaction
     */
    public function releaseHeldTransaction($holdId)
    {
        return session()->forget("transaction_hold.{$holdId}");
    }

    /**
     * Get all held transactions for current user
     */
    public function getHeldTransactions()
    {
        $allHolds = session()->get('transaction_hold', []);
        $userHolds = [];
        $currentUserName = auth()->user()->name ?? 'Unknown';

        foreach ($allHolds as $holdId => $holdData) {
            if ($holdData['held_by'] === $currentUserName) {
                $holdData['id'] = $holdId;
                $holdData['cart'] = [];
                
                // Convert items array to cart format for JavaScript compatibility
                foreach ($holdData['items'] as $item) {
                    $holdData['cart'][$item['product_id']] = $item;
                }
                
                $userHolds[] = $holdData;
            }
        }

        return $userHolds;
    }

    /**
     * Generate receipt data
     */
    public function generateReceiptData($transactionId)
    {
        $transaction = Transaction::with(['customer', 'user', 'transactionItems.product'])
            ->findOrFail($transactionId);

        return [
            'transaction' => $transaction,
            'company' => [
                'name' => config('app.name', 'CoffPOS'),
                'address' => 'Jl. Contoh No. 123, Jakarta',
                'phone' => '021-12345678',
                'email' => 'info@coffpos.com'
            ],
            'receipt_number' => $transaction->transaction_code,
            'date' => $transaction->created_at->format('d/m/Y H:i:s'),
            'cashier' => $transaction->user->name,
            'customer' => $transaction->customer ? $transaction->customer->name : 'Walk-in Customer',
            'items' => $transaction->transactionItems->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal
                ];
            }),
            'totals' => [
                'subtotal' => $transaction->subtotal_amount,
                'discount' => $transaction->discount_amount,
                'tax' => $transaction->tax_amount,
                'total' => $transaction->total_amount
            ],
            'payment' => [
                'method' => ucfirst($transaction->payment_method),
                'amount' => $transaction->payment_amount,
                'change' => $transaction->change_amount
            ]
        ];
    }

    /**
     * Log transaction activity
     */
    private function logTransaction(Transaction $transaction, $action)
    {
        Log::info("Transaction {$action}", [
            'transaction_id' => $transaction->id,
            'transaction_code' => $transaction->transaction_code,
            'total_amount' => $transaction->total_amount,
            'payment_method' => $transaction->payment_method,
            'customer_id' => $transaction->customer_id,
            'user_id' => $transaction->user_id,
            'action' => $action
        ]);
    }

    /**
     * Get daily sales summary
     */
    public function getDailySummary($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        
        $transactions = Transaction::whereDate('created_at', $date)
            ->where('status', 'completed')
            ->get();

        return [
            'date' => $date->format('Y-m-d'),
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->sum('total_amount'),
            'total_items_sold' => $transactions->sum(function ($transaction) {
                return $transaction->transactionItems->sum('quantity');
            }),
            'average_transaction' => $transactions->count() > 0 ? $transactions->avg('total_amount') : 0,
            'payment_methods' => $transactions->groupBy('payment_method')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_amount')
                ];
            }),
            'hourly_sales' => $transactions->groupBy(function ($transaction) {
                return $transaction->created_at->format('H');
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_amount')
                ];
            })
        ];
    }

    /**
     * Create Midtrans Snap Token for digital payment
     */
    public function createMidtransSnapToken(array $cartItems, $customer = null, $orderId = null)
    {
        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $taxAmount = $subtotal * 0.1; // 10% tax
            $totalAmount = $subtotal + $taxAmount;

            // Generate order ID if not provided
            if (!$orderId) {
                $orderId = 'POS-' . time() . '-' . rand(1000, 9999);
            }

            // Prepare transaction details
            $transactionDetails = $this->midtransService->prepareTransactionDetails($orderId, $totalAmount);
            
            // Prepare customer details
            $customerDetails = $this->midtransService->prepareCustomerDetails($customer);
            
            // Prepare item details
            $itemDetails = $this->midtransService->prepareItemDetails($cartItems);
            
            // Add tax as separate item
            $itemDetails[] = [
                'id' => 'TAX',
                'price' => (int) $taxAmount,
                'quantity' => 1,
                'name' => 'Tax (10%)',
            ];

            // Create snap token
            $snapToken = $this->midtransService->createSnapToken($transactionDetails, $customerDetails, $itemDetails);

            return [
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'total_amount' => $totalAmount
            ];

        } catch (Exception $e) {
            Log::error('Error creating Midtrans Snap Token: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process Midtrans payment notification
     */
    public function processMidtransNotification($notification)
    {
        try {
            $status = $this->midtransService->handleNotification($notification);
            
            // Find transaction by order_id
            $transaction = Transaction::where('transaction_code', $notification->order_id)->first();
            
            if ($transaction) {
                switch ($status) {
                    case 'success':
                        $transaction->update([
                            'status' => 'completed',
                            'payment_status' => 'paid',
                            'midtrans_transaction_id' => $notification->transaction_id ?? null,
                            'midtrans_payment_type' => $notification->payment_type ?? null,
                        ]);
                        break;
                    
                    case 'pending':
                        $transaction->update([
                            'status' => 'pending',
                            'payment_status' => 'pending',
                            'midtrans_transaction_id' => $notification->transaction_id ?? null,
                            'midtrans_payment_type' => $notification->payment_type ?? null,
                        ]);
                        break;
                    
                    case 'denied':
                    case 'cancelled':
                    case 'expired':
                        $transaction->update([
                            'status' => 'cancelled',
                            'payment_status' => 'failed',
                            'midtrans_transaction_id' => $notification->transaction_id ?? null,
                            'midtrans_payment_type' => $notification->payment_type ?? null,
                        ]);
                        break;
                }
            }

            return $status;

        } catch (Exception $e) {
            Log::error('Error processing Midtrans notification: ' . $e->getMessage());
            throw $e;
        }
    }
}
