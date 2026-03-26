<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DetalleProducto;
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
    protected $description = 'Fetch the latest BCV rate and update product prices globally in Bs';

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
            
            // Update products
            $products = DetalleProducto::where('precio_usd', '>', 0)->get();
            $count = 0;
            
            /** @var \App\Models\DetalleProducto $detail */
            foreach ($products as $detail) {
                // Calculate new price in Bs
                $newPrice = round($detail->precio_usd * $bcvRate, 2);
                $detail->setAttribute('precio', $newPrice);
                $detail->save();
                $count++;
            }
            
            $this->info("Successfully updated {$count} product prices (Rate: Bs {$bcvRate}).");
            Log::info("UpdateBcvPrices: Updated {$count} products. BCV Rate: {$bcvRate}");
            
        } catch (\Exception $e) {
            $this->error('Exception caught: ' . $e->getMessage());
            Log::error('UpdateBcvPrices: Exception caught.', ['exception' => $e->getMessage()]);
        }
    }
}
