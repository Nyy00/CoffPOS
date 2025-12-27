<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;
use Exception;

class MidtransService
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('midtrans.is_production');
        // Set sanitization on (default)
        Config::$isSanitized = config('midtrans.is_sanitized');
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('midtrans.is_3ds');
        
        // Debug logging
        Log::info('MidtransService initialized', [
            'server_key_set' => !empty(Config::$serverKey),
            'server_key_prefix' => Config::$serverKey ? substr(Config::$serverKey, 0, 15) . '...' : 'NOT SET',
            'is_production' => Config::$isProduction,
            'is_sanitized' => Config::$isSanitized,
            'is_3ds' => Config::$is3ds
        ]);
    }

    /**
     * Create Snap Token for payment
     */
    public function createSnapToken($transactionDetails, $customerDetails = null, $itemDetails = null)
    {
        // Re-initialize config to ensure fresh values
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        
        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'enabled_payments' => [
                'credit_card', 'mandiri_clickpay', 'cimb_clicks',
                'bca_klikbca', 'bca_klikpay', 'bri_epay', 'echannel', 'permata_va',
                'bca_va', 'bni_va', 'bri_va', 'other_va', 'gopay', 'indomaret',
                'danamon_online', 'akulaku', 'shopeepay', 'kredivo', 'uob_ezpay'
            ],
            'vtweb' => []
        ];

        try {
            // Validate configuration
            if (empty(Config::$serverKey)) {
                throw new Exception('Midtrans server key is not configured');
            }

            Log::info('Creating Midtrans Snap Token', [
                'order_id' => $transactionDetails['order_id'] ?? 'unknown',
                'amount' => $transactionDetails['gross_amount'] ?? 0,
                'server_key_prefix' => substr(Config::$serverKey, 0, 15) . '...',
                'is_production' => Config::$isProduction,
                'is_sanitized' => Config::$isSanitized,
                'is_3ds' => Config::$is3ds
            ]);

            $snapToken = Snap::getSnapToken($params);
            
            Log::info('Midtrans Snap Token created successfully', [
                'order_id' => $transactionDetails['order_id'] ?? 'unknown'
            ]);
            
            return $snapToken;
        } catch (Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage(), [
                'params' => $params,
                'config' => [
                    'server_key_prefix' => Config::$serverKey ? substr(Config::$serverKey, 0, 15) . '...' : 'NOT SET',
                    'server_key_set' => !empty(Config::$serverKey),
                    'is_production' => Config::$isProduction,
                    'is_sanitized' => Config::$isSanitized,
                    'is_3ds' => Config::$is3ds
                ]
            ]);
            throw $e;
        }
    }

    /**
     * Get transaction status from Midtrans
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            Log::error('Midtrans Transaction Status Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle notification from Midtrans
     */
    public function handleNotification($notification)
    {
        try {
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            Log::info('Midtrans Notification', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    // TODO: Set payment status in merchant's database to 'Challenge by FDS'
                    return 'challenge';
                } else if ($fraudStatus == 'accept') {
                    // TODO: Set payment status in merchant's database to 'Success'
                    return 'success';
                }
            } else if ($transactionStatus == 'settlement') {
                // TODO: Set payment status in merchant's database to 'Success'
                return 'success';
            } else if ($transactionStatus == 'pending') {
                // TODO: Set payment status in merchant's database to 'Pending'
                return 'pending';
            } else if ($transactionStatus == 'deny') {
                // TODO: Set payment status in merchant's database to 'Denied'
                return 'denied';
            } else if ($transactionStatus == 'expire') {
                // TODO: Set payment status in merchant's database to 'Expired'
                return 'expired';
            } else if ($transactionStatus == 'cancel') {
                // TODO: Set payment status in merchant's database to 'Cancelled'
                return 'cancelled';
            }

            return 'unknown';
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Handling Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Prepare transaction details for Midtrans
     */
    public function prepareTransactionDetails($orderId, $grossAmount)
    {
        return [
            'order_id' => $orderId,
            'gross_amount' => (int) $grossAmount,
        ];
    }

    /**
     * Prepare customer details for Midtrans
     */
    public function prepareCustomerDetails($customer = null)
    {
        if (!$customer) {
            return [
                'first_name' => 'Walk-in Customer',
                'email' => 'walkin@coffpos.com',
                'phone' => '08123456789',
            ];
        }

        return [
            'first_name' => $customer->name ?? 'Customer',
            'email' => $customer->email ?? 'customer@coffpos.com',
            'phone' => $customer->phone ?? '08123456789',
        ];
    }

    /**
     * Prepare item details for Midtrans
     */
    public function prepareItemDetails($cartItems)
    {
        $items = [];
        
        foreach ($cartItems as $item) {
            $items[] = [
                'id' => $item['product_id'],
                'price' => (int) $item['price'],
                'quantity' => (int) $item['quantity'],
                'name' => $item['name'],
            ];
        }

        return $items;
    }
}