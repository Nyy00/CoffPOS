<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display POS interface
     */
    public function index()
    {
        $categories = Category::with('products')->get();
        $products = Product::where('is_available', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->get();
        
        return view('cashier.pos', compact('categories', 'products'));
    }

    /**
     * Search products for POS (API)
     */
    public function searchProducts(Request $request)
    {
        $query = Product::where('is_available', true)
            ->where('stock', '>', 0)
            ->with('category');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->limit(20)->get();

        return response()->json($products);
    }

    /**
     * Add item to cart (session-based)
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (!$product->is_available || $product->stock < $validated['quantity']) {
            return response()->json(['error' => 'Product not available or insufficient stock'], 400);
        }

        $cart = Session::get('pos_cart', []);
        $productId = $validated['product_id'];

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $validated['quantity'];
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $validated['quantity'],
                'image' => $product->image
            ];
        }

        // Check if total quantity exceeds stock
        if ($cart[$productId]['quantity'] > $product->stock) {
            return response()->json(['error' => 'Quantity exceeds available stock'], 400);
        }

        Session::put('pos_cart', $cart);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'total_items' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = Session::get('pos_cart', []);
        $productId = $validated['product_id'];

        if ($validated['quantity'] == 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::findOrFail($productId);
            
            if ($validated['quantity'] > $product->stock) {
                return response()->json(['error' => 'Quantity exceeds available stock'], 400);
            }

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $validated['quantity'];
            }
        }

        Session::put('pos_cart', $cart);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'total_items' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = Session::get('pos_cart', []);
        unset($cart[$validated['product_id']]);
        Session::put('pos_cart', $cart);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'total_items' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        Session::forget('pos_cart');

        return response()->json(['success' => true]);
    }

    /**
     * Force clear cart and all related sessions
     */
    public function forceClearCart()
    {
        // Clear all possible cart-related sessions
        Session::forget('pos_cart');
        Session::forget('cart');
        Session::forget('shopping_cart');
        
        // Force save session
        Session::save();
        
        return response()->json([
            'success' => true,
            'message' => 'Cart forcefully cleared',
            'debug' => [
                'session_id' => Session::getId(),
                'remaining_keys' => array_keys(Session::all())
            ]
        ]);
    }

    /**
     * Get current cart items
     */
    public function getCartItems()
    {
        $cart = Session::get('pos_cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'cart' => $cart,
            'subtotal' => $subtotal,
            'total_items' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    /**
     * Process transaction with enhanced validation
     */
    public function processTransaction(Request $request)
    {
        \Log::info('Process transaction called', [
            'user_id' => auth()->id(),
            'request_data' => $request->all()
        ]);
        
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'payment.method' => 'required|in:cash,debit,credit,e-wallet,qris',
            'payment.amount' => 'required|numeric|min:0',
            'payment.reference' => 'nullable|string|max:255',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'use_loyalty_points' => 'nullable|boolean',
            'loyalty_points_used' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:500'
        ]);

        $cart = Session::get('pos_cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        try {
            // Convert cart to items array
            $items = array_values($cart);

            $transactionData = [
                'customer_id' => $validated['customer_id'],
                'items' => $items,
                'payment' => $validated['payment'],
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'discount_percent' => $validated['discount_percent'] ?? 0,
                'tax_percent' => $validated['tax_percent'] ?? 10,
                'use_loyalty_points' => $validated['use_loyalty_points'] ?? false,
                'loyalty_points_used' => $validated['loyalty_points_used'] ?? 0,
                'notes' => $validated['notes']
            ];

            $transaction = $this->transactionService->processPOSTransaction($transactionData);

            // Clear cart after successful transaction
            Session::forget('pos_cart');

            return response()->json([
                'success' => true,
                'transaction' => $transaction,
                'receipt_data' => $this->transactionService->generateReceiptData($transaction->id),
                'message' => 'Transaction processed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Print receipt
     */
    public function printReceipt(Transaction $transaction)
    {
        $transaction->load(['customer', 'user', 'transactionItems.product']);
        
        return view('receipts.transaction', compact('transaction'));
    }

    /**
     * Preview receipt
     */
    public function previewReceipt(Transaction $transaction)
    {
        $transaction->load(['customer', 'user', 'transactionItems.product']);
        
        return response()->json([
            'transaction' => $transaction,
            'html' => view('receipts.transaction', compact('transaction'))->render()
        ]);
    }

    /**
     * Hold current transaction using TransactionService
     */
    public function holdTransaction(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'customer_name' => 'nullable|string|max:255',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500'
        ]);

        $cart = Session::get('pos_cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        try {
            $holdData = [
                'items' => array_values($cart),
                'customer_name' => $validated['customer_name'],
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'notes' => $validated['notes'],
                'reason' => $validated['reason']
            ];

            $holdId = $this->transactionService->holdTransaction($holdData);

            // Clear current cart
            Session::forget('pos_cart');

            return response()->json([
                'success' => true,
                'message' => 'Transaction held successfully',
                'hold_id' => $holdId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get held transactions using TransactionService
     */
    public function getHeldTransactions()
    {
        try {
            $heldTransactions = $this->transactionService->getHeldTransactions();
            
            return response()->json([
                'success' => true,
                'held_transactions' => $heldTransactions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Resume held transaction using TransactionService
     */
    public function resumeTransaction($holdId)
    {
        try {
            $heldTransaction = $this->transactionService->getHeldTransaction($holdId);
            
            if (!$heldTransaction) {
                return response()->json(['error' => 'Held transaction not found'], 404);
            }

            // Convert items back to cart format
            $cart = [];
            foreach ($heldTransaction['items'] as $item) {
                $cart[$item['product_id']] = $item;
            }

            // Restore cart
            Session::put('pos_cart', $cart);
            
            // Release held transaction
            $this->transactionService->releaseHeldTransaction($holdId);

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'customer_name' => $heldTransaction['customer_name'],
                'discount_amount' => $heldTransaction['discount_amount'],
                'notes' => $heldTransaction['notes'],
                'message' => 'Transaction resumed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get cart total
     */
    public function getCartTotal()
    {
        $cart = Session::get('pos_cart', []);
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }

        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;

        return response()->json([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'total_items' => $totalItems
        ]);
    }

    /**
     * Delete held transaction using TransactionService
     */
    public function deleteHeldTransaction($holdId)
    {
        try {
            // Check if held transaction exists before deleting
            $heldTransactions = $this->transactionService->getHeldTransactions();
            $exists = false;
            foreach ($heldTransactions as $transaction) {
                if ($transaction['id'] == $holdId) {
                    $exists = true;
                    break;
                }
            }
            
            if (!$exists) {
                return response()->json(['error' => 'Held transaction not found'], 404);
            }
            
            $this->transactionService->releaseHeldTransaction($holdId);

            return response()->json([
                'success' => true,
                'message' => 'Held transaction deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Calculate cart totals with tax and discount
     */
    public function calculateTotals(Request $request)
    {
        $validated = $request->validate([
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'loyalty_points_used' => 'nullable|integer|min:0',
            'customer_id' => 'nullable|exists:customers,id'
        ]);

        $cart = Session::get('pos_cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        try {
            $items = array_values($cart);
            $totals = $this->transactionService->calculateTotal($items, 
                $validated['discount_amount'] ?? 0, 
                $validated['tax_percent'] ?? 10
            );

            // Apply loyalty discount if applicable
            if ($validated['customer_id'] && $validated['loyalty_points_used']) {
                $customer = Customer::find($validated['customer_id']);
                if ($customer && $customer->points >= $validated['loyalty_points_used']) {
                    $loyaltyDiscount = min($validated['loyalty_points_used'], $totals['total'] * 0.5);
                    $totals['loyalty_discount'] = $loyaltyDiscount;
                    $totals['total'] -= $loyaltyDiscount;
                }
            }

            return response()->json([
                'success' => true,
                'totals' => $totals,
                'cart_items' => count($cart),
                'total_quantity' => array_sum(array_column($cart, 'quantity'))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get customer loyalty info
     */
    public function getCustomerLoyalty($customerId)
    {
        try {
            $customer = Customer::with(['transactions' => function($query) {
                $query->where('status', 'completed')
                      ->orderBy('created_at', 'desc')
                      ->limit(5);
            }])->findOrFail($customerId);

            $totalSpent = $customer->transactions()->sum('total_amount');
            $totalTransactions = $customer->transactions()->count();

            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'points' => $customer->points,
                    'total_spent' => $totalSpent,
                    'total_transactions' => $totalTransactions,
                    'average_transaction' => $totalTransactions > 0 ? $totalSpent / $totalTransactions : 0,
                    'recent_transactions' => $customer->transactions
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Customer not found'
            ], 404);
        }
    }

    /**
     * Quick add customer for POS
     */
    public function quickAddCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'email' => 'nullable|email|max:255|unique:customers,email'
        ]);

        try {
            $customer = Customer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'points' => 0
            ]);

            return response()->json([
                'success' => true,
                'customer' => $customer,
                'message' => 'Customer added successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to add customer: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Search customers for POS
     */
    public function searchCustomers(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->select('id', 'name', 'phone', 'email', 'points')
                          ->orderBy('name')
                          ->limit(10)
                          ->get();

        return response()->json([
            'success' => true,
            'customers' => $customers
        ]);
    }

    /**
     * Get receipt data for printing
     */
    public function getReceiptData($transactionId)
    {
        try {
            $receiptData = $this->transactionService->generateReceiptData($transactionId);
            
            return response()->json([
                'success' => true,
                'receipt_data' => $receiptData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Transaction not found'
            ], 404);
        }
    }

    /**
     * Get daily sales summary
     */
    public function getDailySummary(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        
        try {
            $summary = $this->transactionService->getDailySummary($date);
            
            return response()->json([
                'success' => true,
                'summary' => $summary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Create Midtrans Snap Token for digital payment
     */
    public function createMidtransToken(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id'
        ]);

        $cart = Session::get('pos_cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'error' => 'Cart is empty'
            ], 400);
        }

        // Check if Midtrans keys are properly configured
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        
        if (empty($serverKey) || empty($clientKey) || 
            $serverKey === 'your-sandbox-server-key-here' || 
            $clientKey === 'your-sandbox-client-key-here') {
            
            return response()->json([
                'success' => false,
                'error' => 'Midtrans is not properly configured. Please set valid MIDTRANS_SERVER_KEY and MIDTRANS_CLIENT_KEY in your .env file. Visit https://dashboard.sandbox.midtrans.com to get your keys.'
            ], 500);
        }

        try {
            // Get customer if provided
            $customer = null;
            if ($validated['customer_id']) {
                $customer = Customer::find($validated['customer_id']);
            }

            // Create snap token
            $result = $this->transactionService->createMidtransSnapToken($cart, $customer);

            return response()->json([
                'success' => true,
                'snap_token' => $result['snap_token'],
                'order_id' => $result['order_id'],
                'total_amount' => $result['total_amount'],
                'client_key' => config('midtrans.client_key')
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating Midtrans token', [
                'error' => $e->getMessage(),
                'cart' => $cart,
                'customer_id' => $validated['customer_id'] ?? null
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to create payment token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Midtrans payment notification (webhook)
     */
    public function handleMidtransNotification(Request $request)
    {
        try {
            $notification = $request->all();
            
            // Convert to object for compatibility
            $notification = (object) $notification;
            
            $status = $this->transactionService->processMidtransNotification($notification);
            
            return response()->json([
                'success' => true,
                'status' => $status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Process Midtrans payment success
     */
    public function processMidtransPayment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string',
            'transaction_status' => 'required|string',
            'transaction_id' => 'nullable|string',
            'payment_type' => 'nullable|string',
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $cart = Session::get('pos_cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'error' => 'Cart is empty'
            ], 400);
        }

        try {
            // Calculate payment amount from cart
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $taxAmount = $subtotal * 0.1; // 10% tax
            $totalAmount = $subtotal + $taxAmount;

            // Prepare transaction data for POS transaction
            $transactionData = [
                'customer_id' => $validated['customer_id'],
                'items' => array_values($cart),
                'payment' => [
                    'method' => 'digital',
                    'amount' => $totalAmount, // Set correct payment amount
                    'reference' => $validated['order_id']
                ],
                'discount_amount' => 0,
                'discount_percent' => 0,
                'tax_percent' => 10,
                'use_loyalty_points' => false,
                'loyalty_points_used' => 0,
                'notes' => $validated['notes'] ?? ''
            ];

            // Create transaction using the POS transaction service
            $transaction = $this->transactionService->processPOSTransaction($transactionData);

            // Update transaction with Midtrans specific data
            $transaction->update([
                'transaction_code' => $validated['order_id'],
                'midtrans_transaction_id' => $validated['transaction_id'],
                'midtrans_payment_type' => $validated['payment_type'],
                'status' => $validated['transaction_status'] === 'settlement' ? 'completed' : 'pending',
                'payment_status' => $validated['transaction_status'] === 'settlement' ? 'paid' : 'pending'
            ]);

            // Clear cart
            Session::forget('pos_cart');

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'transaction' => $transaction->load(['customer', 'user', 'transactionItems.product'])
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing Midtrans payment', [
                'error' => $e->getMessage(),
                'validated' => $validated,
                'cart' => $cart,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}