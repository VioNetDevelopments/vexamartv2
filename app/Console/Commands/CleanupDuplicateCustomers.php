<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:cleanup-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicate customers from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding duplicate customers...');
        
        $duplicates = DB::table('customers')
            ->select('email', DB::raw('COUNT(*) as count'))
            ->whereNotNull('email')
            ->whereNull('deleted_at') // Only check active records
            ->groupBy('email')
            ->having('count', '>', 1)
            ->get();
        
        if ($duplicates->isEmpty()) {
            $this->info('No duplicates found!');
            return 0;
        }
        
        $this->warn("Found {$duplicates->count()} duplicate email addresses");
        
        $deletedCount = 0;
        
        foreach ($duplicates as $duplicate) {
            $this->info("Processing: {$duplicate->email}");
            
            // Get all customers with this email
            $customers = Customer::where('email', $duplicate->email)
                ->orderBy('id', 'asc')
                ->get();
            
            // Keep the first one, delete the rest
            for ($i = 1; $i < $customers->count(); $i++) {
                $customer = $customers[$i];
                
                // Check if customer has transactions
                if ($customer->transactions()->count() > 0) {
                    $this->warn("  ⚠️  Skipping ID {$customer->id} - has transactions");
                    continue;
                }
                
                $customer->delete();
                $deletedCount++;
                $this->info("  ✅ Deleted customer ID {$customer->id}");
            }
        }
        
        $this->info("Cleanup complete! Deleted {$deletedCount} duplicate customers.");
        
        return 0;
    }
}
