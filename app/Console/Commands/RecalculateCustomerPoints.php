<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class RecalculateCustomerPoints extends Command
{
    protected $signature = 'customer:recalculate-points {customer_id?} {--fix : Actually fix the points}';
    protected $description = 'Recalculate customer loyalty points based on transaction history';

    public function handle()
    {
        $customerId = $this->argument('customer_id');
        $shouldFix = $this->option('fix');
        
        if (!$shouldFix) {
            $this->warn('Running in DRY RUN mode. Use --fix to actually update points.');
        }

        if ($customerId) {
            $this->recalculateCustomer($customerId, $shouldFix);
        } else {
            $this->recalculateAllCustomers($shouldFix);
        }
    }

    private function recalculateCustomer($customerId, $shouldFix = false)
    {
        $customer = Customer::find($customerId);
        
        if (!$customer) {
            $this->error("Customer with ID {$customerId} not found");
            return;
        }

        $this->info("Recalculating points for: {$customer->name}");
        
        // Get all transactions
        $transactions = Transaction::where('customer_id', $customerId)
            ->orderBy('created_at', 'asc')
            ->get();

        $totalPointsEarned = 0;
        $totalPointsUsed = 0;

        foreach ($transactions as $transaction) {
            // Calculate points earned (1 point per 10,000 IDR)
            $pointsEarned = floor($transaction->total_amount / 10000);
            $totalPointsEarned += $pointsEarned;
            
            // Points used
            $pointsUsed = $transaction->loyalty_points_used ?? 0;
            $totalPointsUsed += $pointsUsed;
        }

        $correctPoints = $totalPointsEarned - $totalPointsUsed;
        $currentPoints = $customer->points;
        
        $this->info("Current Points: {$currentPoints}");
        $this->info("Correct Points: {$correctPoints}");
        $this->info("Difference: " . ($correctPoints - $currentPoints));

        if ($correctPoints != $currentPoints) {
            if ($shouldFix) {
                DB::transaction(function () use ($customer, $correctPoints) {
                    $customer->update(['points' => $correctPoints]);
                });
                
                $this->info("✅ Points updated from {$currentPoints} to {$correctPoints}");
            } else {
                $this->warn("Would update points from {$currentPoints} to {$correctPoints}");
            }
        } else {
            $this->info("✅ Points are already correct");
        }
    }

    private function recalculateAllCustomers($shouldFix = false)
    {
        $customers = Customer::whereHas('transactions')->get();
        
        $this->info("Processing " . $customers->count() . " customers...");
        
        $updated = 0;
        $correct = 0;
        
        foreach ($customers as $customer) {
            $transactions = Transaction::where('customer_id', $customer->id)->get();
            
            $totalPointsEarned = 0;
            $totalPointsUsed = 0;

            foreach ($transactions as $transaction) {
                $pointsEarned = floor($transaction->total_amount / 10000);
                $totalPointsEarned += $pointsEarned;
                
                $pointsUsed = $transaction->loyalty_points_used ?? 0;
                $totalPointsUsed += $pointsUsed;
            }

            $correctPoints = $totalPointsEarned - $totalPointsUsed;
            $currentPoints = $customer->points;
            
            if ($correctPoints != $currentPoints) {
                $this->line("Customer: {$customer->name} - Current: {$currentPoints}, Correct: {$correctPoints}");
                
                if ($shouldFix) {
                    $customer->update(['points' => $correctPoints]);
                    $updated++;
                } else {
                    $updated++;
                }
            } else {
                $correct++;
            }
        }

        if ($shouldFix) {
            $this->info("✅ Updated {$updated} customers, {$correct} were already correct");
        } else {
            $this->info("Would update {$updated} customers, {$correct} are already correct");
            $this->warn("Use --fix to actually update the points");
        }
    }
}