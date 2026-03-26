<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\DetalleProducto;
use Illuminate\Database\Seeder;

class KitsSeeder extends Seeder
{
    public function run(): void
    {
        $catalogo = [
            // Categoria: kits
            [
                'nombre'      => 'Kit de Inicio a la Costura',
                'descripcion' => 'Todo lo fundamental para empezar a coser: hilos básicos, alfileres, agujas, tijeras pequeñas y cinta métrica.',
                'categoria'   => 'kits',
                'variantes'   => [
                    ['color' => 'Surtido', 'grosor' => null,  'marca' => 'Prym', 'cm' => null, 'precio' => 35.00, 'stock' => 15],
                ],
            ],
            [
                'nombre'      => 'Kit de Tejido Básico (Bufanda)',
                'descripcion' => 'Aprende a tejer tu primera bufanda. Incluye 2 ovillos de lana gruesa, agujas de bambú y un manual de instrucciones.',
                'categoria'   => 'kits',
                'variantes'   => [
                    ['color' => 'Rosa/Beige', 'grosor' => '10 mm', 'marca' => 'Katia', 'cm' => null, 'precio' => 28.50, 'stock' => 10],
                ],
            ],
        ];

        foreach ($catalogo as $item) {
            $producto = Producto::create([
                'nombre'      => $item['nombre'],
                'descripcion' => $item['descripcion'],
                'categoria'   => $item['categoria'],
            ]);

            foreach ($item['variantes'] as $v) {
                DetalleProducto::create([
                    'producto_id'          => $producto->id,
                    'color'                => $v['color']               ?? null,
                    'grosor'               => $v['grosor']              ?? null,
                    'cm'                   => $v['cm']                  ?? null,
                    'marca'                => $v['marca']               ?? null,
                    'precio'               => $v['precio'],
                    'stock'                => $v['stock'],
                    'en_oferta'            => false,
                    'descuento_porcentaje' => 0,
                ]);
            }
        }
    }
}
