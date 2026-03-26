<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\DetalleProducto;
use Illuminate\Database\Seeder;

class MoreProductsSeeder extends Seeder
{
    public function run(): void
    {
        $catalogo = [
            // Categoria: manualidades
            [
                'nombre'      => 'Set de Pinturas Acrílicas',
                'descripcion' => 'Caja con 12 tubos de pintura acrílica de alta pigmentación, ideal para tela, madera y lienzo.',
                'categoria'   => 'manualidades',
                'variantes'   => [
                    ['color' => 'Surtido', 'grosor' => null,  'marca' => 'Pebeo', 'cm' => null, 'precio' => 25.00, 'stock' => 15],
                ],
            ],
            [
                'nombre'      => 'Tijeras Zig Zag para Papel y Tela',
                'descripcion' => 'Tijeras con hoja dentada para realizar cortes creativos y evitar deshilachados en manualidades.',
                'categoria'   => 'manualidades',
                'variantes'   => [
                    ['color' => 'Rosa/Gris', 'grosor' => null, 'marca' => 'Fiskars', 'cm' => 22, 'precio' => 18.50, 'stock' => 12],
                ],
            ],

            // Categoria: tejido
            [
                'nombre'      => 'Marcadores de Puntos',
                'descripcion' => 'Set de 50 marcadores de puntos coloridos tipo candado, indispensables para seguir patrones de tejido.',
                'categoria'   => 'tejido',
                'variantes'   => [
                    ['color' => 'Multicolor', 'grosor' => null, 'marca' => 'Clover', 'cm' => null, 'precio' => 8.00, 'stock' => 45],
                ],
            ],
            [
                'nombre'      => 'Hilo de Algodón Rústico',
                'descripcion' => 'Algodón sin peinar, textura mate y natural, ideal para proyectos de macramé y amigurumis.',
                'categoria'   => 'tejido',
                'variantes'   => [
                    ['color' => 'Mostaza', 'grosor' => '3 mm', 'marca' => 'Katia', 'cm' => 150, 'precio' => 12.00, 'stock' => 20],
                    ['color' => 'Terracota', 'grosor' => '3 mm', 'marca' => 'Katia', 'cm' => 150, 'precio' => 12.00, 'stock' => 15],
                ],
            ],

            // Categoria: costura
            [
                'nombre'      => 'Cinta Métrica Retráctil',
                'descripcion' => 'Cinta métrica flexible de doble cara en centímetros y pulgadas. Botón de retroceso automático.',
                'categoria'   => 'costura',
                'variantes'   => [
                    ['color' => 'Azul Pastel', 'grosor' => null, 'marca' => 'Prym', 'cm' => 150, 'precio' => 6.50, 'stock' => 30],
                ],
            ],
            [
                'nombre'      => 'Set de Agujas para Máquina (Universal)',
                'descripcion' => 'Estuche mixto de 10 agujas universales para máquina de coser familiar, varios grosores (70 a 100).',
                'categoria'   => 'costura',
                'variantes'   => [
                    ['color' => 'Plateado', 'grosor' => 'Mixto', 'marca' => 'Schmetz', 'cm' => null, 'precio' => 5.50, 'stock' => 40],
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
