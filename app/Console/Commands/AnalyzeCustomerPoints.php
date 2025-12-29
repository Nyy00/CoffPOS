<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Transaction;

class AnalyzeCustomerPoints extends Command
{
    protected $signature = 'customer:analyze-points {customer_id?}';
    protected $description = 'Analyze customer loyalty points calculation';

    public function handle()
    {
        $customerId = $this->argument('customer_id');
        
        if ($customerId) {
            $this->analyzeCustomer($customerId);
        } else {
            $this->info('Analyzing all customers with point discrepancies...');
            $this->analyzeAllCustomers();
        }
    }

    private function analyzeCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        
        if (!$customer) {
            $this->error("Customer with ID {$customerId} not found");
            return;
        }

        $this->info("=== Customer Analysis: {$customer->name} ===");
        $this->info("Customer ID: {$customer->id}");
        $this->info("Current Points: {$customer->points}");
        
        // Get all transactions
        $transactions = Transaction::where('customer_id', $customerId)
            ->orderBy('created_at', 'asc')
            ->get();

        $this->info("Total Transactions: " . $transactions->count());
        
        $totalSpent = 0;
        $totalPointsEarned = 0;
        $totalPointsUsed = 0;
        $calculatedPoints = 0;

        $this->info("\n=== Transaction Details ===");
        $this->table(
            ['Code', 'Date', 'Amount', 'Points Earned', 'Points Used', 'Running Total'],
            $transactions->map(function ($transaction) use (&$totalSpent, &$totalPointsEarned, &$totalPointsUsed, &$calculatedPoints) {
                $totalSpent += $transaction->total_amount;
                
                // Calculate points earned (1 point per 10,000 IDR)
                $pointsEarned = floor($transaction->total_amount / 10000);
                $totalPointsEarned += $pointsEarned;
                
                // Points used (if any)
                $pointsUsed = $transaction->loyalty_points_used ?? 0;
                $totalPointsUsed += $pointsUsed;
                
                // Running total
                $calculatedPoints += $pointsEarned - $pointsUsed;
                
                return [
                    $transaction->transaction_code,
                    $transaction->created_at->format('Y-m-d'),
                    'Rp ' . number_format($transaction->total_amount),
                    $pointsEarned,
                    $pointsUsed,
                    $calculatedPoints
                ];
            })
        );

        $this->info("\n=== Summary ===");
        $this->info("Total Spent: Rp " . number_format($totalSpent));
        $this->info("Total Points Earned: {$totalPointsEarned}");
        $this->info("Total Points Used: {$totalPointsUsed}");
        $this->info("Calculated Points Balance: " . ($totalPointsEarned - $totalPointsUsed));
        $this->info("Actual Points Balance: {$customer->points}");
        
        $difference = $customer->points - ($totalPointsEarned - $totalPointsUsed);
        
        if ($difference != 0) {
            $this->error("DISCREPANCY FOUND: {$difference} points");
            
            if ($difference > 0) {
                $this->warn("Customer has MORE points than calculated (possible manual adjustment)");
            } else {
                $this->warn("Customer has FEWER points than calculated (possible system error)");
            }
        } else {
            $this->info("✅ Points calculation is CORRECT");
        }

        // Expected points based on total spent
        $expectedPoints = floor($totalSpent / 10000);
        $this->info("\nExpected Points (based on total spent): {$expectedPoints}");
        
        if ($expectedPoints != $totalPointsEarned) {
            $this->warn("Note: Expected points differ from earned points due to individual transaction rounding");
        }
    }

    private function analyzeAllCustomers()
    {
        $customers = Customer::whereHas('transactions')->with('transactions')->get();
        
        $discrepancies = [];
        
        foreach ($customers as $customer) {
            $totalSpent = $customer->transactions->sum('total_amount');
            $totalPointsEarned = $customer->transactions->sum(function ($transaction) {
                return floor($transaction->total_amount / 10000);
            });
            $totalPointsUsed = $customer->transactions->sum('loyalty_points_used');
            
            $calculatedPoints = $totalPointsEarned - $totalPointsUsed;
            $actualPoints = $customer->points;
            
            if ($calculatedPoints != $actualPoints) {
                $discrepancies[] = [
                    'ID' => $customer->id,
                    'Name' => $customer->name,
                    'Actual' => $actualPoints,
                    'Calculated' => $calculatedPoints,
                    'Difference' => $actualPoints - $calculatedPoints,
                    'Total Spent' => 'Rp ' . number_format($totalSpent)
                ];
            }
        }

        if (empty($discrepancies)) {
            $this->info("✅ All customers have correct points calculation");
        } else {
            $this->error("Found " . count($discrepancies) . " customers with point discrepancies:");
            $this->table(
                ['ID', 'Name', 'Actual Points', 'Calculated Points', 'Difference', 'Total Spent'],
                $discrepancies
            );
        }
    }
}