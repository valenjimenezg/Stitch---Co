<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateBcvPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bcv:update-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the latest BCV rate and update the global cache variable';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching BCV rate from ve.dolarapi.com...');
        
        try {
            $response = Http::timeout(10)->get('https://ve.dolarapi.com/v1/dolares/oficial');
            
            if (!$response->successful()) {
                $this->error('Failed to fetch BCV rate. API returned status: ' . $response->status());
                Log::error('UpdateBcvPrices: Failed to fetch BCV rate.', ['status' => $response->status()]);
                return;
            }
            
            $data = $response->json();
            $bcvRate = $data['promedio'] ?? null;
            
            if (!$bcvRate) {
                $this->error('BCV rate not found in the response.');
                return;
            }
            
            // Format rate
            $bcvRate = (float) $bcvRate;
            
            $this->info("Current BCV Rate: Bs. {$bcvRate}");
            
            // Cache the rate forever (until next update)
            Cache::forever('bcv_rate', $bcvRate);
            Cache::forever('bcv_last_update', now());
            
            // In the strict 12-table ERP, we calculate prices dynamically using Cache at runtime.
            // There is no need to run N+1 update queries on products.
            $this->info("Successfully updated global BCV Rate to {$bcvRate}.");
            Log::info("UpdateBcvPrices: Updated BCV Rate globally to {$bcvRate}");
            
        } catch (\Exception $e) {
            $this->error('Exception caught: ' . $e->getMessage());
            Log::error('UpdateBcvPrices: Exception caught.', ['exception' => $e->getMessage()]);
        }
    }
}
