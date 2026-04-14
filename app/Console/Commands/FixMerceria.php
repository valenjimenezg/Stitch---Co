<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoVariante;

class FixMerceria extends Command
{
    protected $signature = 'fix:merceria';
    protected $description = 'Fix Mercería y Botones';

    public function handle()
    {
        $catBotones = Categoria::where('nombre', 'Botones')->first();
        if ($catBotones) {
            $catBotones->nombre = 'Mercería y Botones';
            $catBotones->save();
        }

        // Add the needles and pins back since the user wants them in the platform!
        $needleImages = [
            'img/productos/1775514610_3.jpg', // pink case
            'img/productos/1775514658_4.jpg', // wooden
            'img/productos/1775514705_5.jpg', // gray case
            'img/productos/1775514969_kit4.jpg', // pins?
        ];

        $titles = [
            'Set de Agujas Profesionales',
            'Agujas de Crochet de Bambú',
            'Kit Agujas Circulares',
            'Alfileres de Cabeza de Vidrio'
        ];

        foreach ($needleImages as $idx => $img) {
            $prod = Producto::create([
                'nombre' => $titles[$idx],
                'descripcion' => 'Insumos de mercería de alta calidad.',
                'categoria_id' => $catBotones->id,
                'proveedor_id' => 1,
                'sku' => 'AGUJA-' . rand(1000, 9999),
                'activo' => true
            ]);

            ProductoVariante::create([
                'producto_id' => $prod->id,
                'proveedor_id' => 1,
                'color' => 'Estándar',
                'stock_base' => rand(10, 50),
                'precio' => rand(150, 450) / 100,
                'imagen' => $img,
                'en_oferta' => false,
                'descuento_porcentaje' => null
            ]);
        }

        $this->info("¡Categoría renombrada y agujas añadidas!");
    }
}
