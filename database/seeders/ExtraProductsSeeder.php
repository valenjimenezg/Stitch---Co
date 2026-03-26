<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExtraProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalogo = [
            // Categoria: manualidades
            [
                'nombre' => 'Kit de Bordado Básico',
                'descripcion' => 'Todo lo necesario para iniciar en el mundo del bordado. Incluye bastidor, agujas, hilos y patrones.',
                'categoria' => 'manualidades',
                'variantes' => [
                    ['color' => 'Multicolor', 'grosor' => null,  'marca' => 'DMC',      'cm' => 15, 'precio' => 45.00, 'stock' => 15],
                    ['color' => 'Pastel',     'grosor' => null,  'marca' => 'DMC',      'cm' => 15, 'precio' => 45.00, 'stock' => 10, 'en_oferta' => true, 'descuento_porcentaje' => 10],
                ],
            ],
            [
                'nombre' => 'Pistola de Silicona Caliente',
                'descripcion' => 'Pistola de pegamento rápido, ideal para ensamblar manualidades. Incluye 5 barras de repuesto.',
                'categoria' => 'manualidades',
                'variantes' => [
                    ['color' => 'Azul',       'grosor' => null,  'marca' => 'Bosch',    'cm' => null, 'precio' => 85.00, 'stock' => 8],
                ],
            ],
            // Categoria: tejido
            [
                'nombre' => 'Lana Gruesa Chunky',
                'descripcion' => 'Lana extra gruesa ideal para tejer con los dedos o con agujas grandes. Muy suave para mantas.',
                'categoria' => 'tejido',
                'variantes' => [
                    ['color' => 'Rosa Viejo', 'grosor' => '15 mm', 'marca' => 'Katia',  'cm' => 50, 'precio' => 32.00, 'stock' => 20],
                    ['color' => 'Beige',      'grosor' => '15 mm', 'marca' => 'Katia',  'cm' => 50, 'precio' => 32.00, 'stock' => 12],
                ],
            ],
            [
                'nombre' => 'Agujas Circulares Intercambiables',
                'descripcion' => 'Set de agujas circulares de madera para tejido de suéteres y cuellos. Cables flexibles.',
                'categoria' => 'tejido',
                'variantes' => [
                    ['color' => 'Madera',     'grosor' => 'Var',   'marca' => 'KnitPro', 'cm' => 80, 'precio' => 120.00, 'stock' => 5, 'en_oferta' => true, 'descuento_porcentaje' => 15],
                ],
            ],
            // Categoria: costura
            [
                'nombre' => 'Alfileres con Cabeza de Vidrio',
                'descripcion' => 'Caja de 100 alfileres profesionales resistentes al calor de la plancha, no dejan marca.',
                'categoria' => 'costura',
                'variantes' => [
                    ['color' => 'Surtido',    'grosor' => '0.5 mm', 'marca' => 'Prym',   'cm' => 3, 'precio' => 15.00, 'stock' => 50],
                ],
            ],
            [
                'nombre' => 'Regla de Patronaje Curva Francesa',
                'descripcion' => 'Regla transparente y flexible para dibujar sisas, escotes y curvas suaves en patronaje.',
                'categoria' => 'costura',
                'variantes' => [
                    ['color' => 'Transparente', 'grosor' => null,   'marca' => 'Fiskars', 'cm' => 30, 'precio' => 25.00, 'stock' => 18],
                ],
            ],
        ];

        foreach ($catalogo as $item) {
            $producto = \App\Models\Producto::create([
                'nombre' => $item['nombre'],
                'descripcion' => $item['descripcion'],
                'categoria' => $item['categoria'],
            ]);

            foreach ($item['variantes'] as $v) {
                \App\Models\DetalleProducto::create([
                    'producto_id' => $producto->id,
                    'color' => $v['color'] ?? null,
                    'grosor' => $v['grosor'] ?? null,
                    'cm' => $v['cm'] ?? null,
                    'marca' => $v['marca'] ?? null,
                    'precio' => $v['precio'],
                    'stock' => $v['stock'],
                    'en_oferta' => $v['en_oferta'] ?? false,
                    'descuento_porcentaje' => $v['descuento_porcentaje'] ?? 0,
                ]);
            }
        }
    }
}
