<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductoVariante;

class UpdatePrices extends Command
{
    protected $signature = 'update:prices';
    protected $description = 'Updates prices to realistic haberdashery values ($0.50 to $4.50)';

    public function handle()
    {
        $variantes = ProductoVariante::all();
        foreach ($variantes as $v) {
            $v->precio = rand(10, 850) / 100; // entre $0.10 y $8.50
            if ($v->en_oferta) {
                $v->descuento_porcentaje = rand(5, 20); // 5% a 20%
            }
            $v->save();
        }
        $this->info("¡Precios actualizados a la realidad de una mercería!");
    }
}
