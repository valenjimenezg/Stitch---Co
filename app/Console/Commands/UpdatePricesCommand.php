<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdatePricesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productos = \App\Models\Producto::with('detalleProductos')->get();

        foreach ($productos as $producto) {
            if ($producto->detalleProductos->isEmpty()) continue;

            switch (strtolower($producto->categoria)) {
                case 'telas':
                    $base_price = mt_rand(350, 1200) / 100;
                    break;
                case 'costura':
                    $base_price = mt_rand(100, 800) / 100;
                    break;
                case 'tejido':
                    $base_price = mt_rand(250, 600) / 100;
                    break;
                case 'accesorios':
                case 'merceria':
                default:
                    $base_price = mt_rand(50, 400) / 100;
                    break;
            }

            foreach ($producto->detalleProductos as $detalle) {
                $precio = $base_price + (mt_rand(0, 100) / 100);
                $detalle->precio = $precio;
                $detalle->timestamps = false;
                $detalle->save();
            }
        }
        $this->info("Precios actualizados exitosamente.");
    }
}
