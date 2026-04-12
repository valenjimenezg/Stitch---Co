<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductoVariante;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiamos viejas asociaciones de categorías creadas
        ProductoVariante::query()->delete();
        Producto::query()->delete();
        Categoria::query()->delete();

        $proveedoresIds = Proveedor::pluck('id')->toArray();

        $catalogo = [
            [
                'nombre'      => 'Hilo de Algodón Premium',
                'descripcion' => 'Hilo de algodón 100% natural, ideal para bordar a mano y a máquina.',
                'categoria'   => 'lana',
                'variantes'   => [
                    ['color' => 'Rojo',     'grosor' => '1 mm', 'marca' => 'DMC',      'precio' => 12.50, 'stock' => 100, 'unidad_medida' => 'Rollo', 'factor_conversion' => 1],
                    ['color' => 'Azul',     'grosor' => '1 mm', 'marca' => 'DMC',      'precio' => 12.50, 'stock' => 80,  'unidad_medida' => 'Rollo', 'factor_conversion' => 1],
                    ['color' => 'Blanco',   'grosor' => '1 mm', 'marca' => 'DMC',      'precio' => 12.00, 'stock' => 150, 'unidad_medida' => 'Rollo', 'factor_conversion' => 1, 'en_oferta' => true, 'descuento_porcentaje' => 10],
                ],
            ],
            [
                'nombre'      => 'Tela de Lino Natural',
                'descripcion' => 'Lino puro de alta calidad. Perfecto para ropa de verano y decoración del hogar.',
                'categoria'   => 'tela',
                'variantes'   => [
                    ['color' => 'Natural',    'grosor' => '0.5 mm', 'marca' => 'Fabricato', 'precio' => 45.00, 'stock' => 50, 'unidad_medida' => 'Metro', 'factor_conversion' => 1],
                    ['color' => 'Blanco',     'grosor' => '0.5 mm', 'marca' => 'Fabricato', 'precio' => 45.00, 'stock' => 35, 'unidad_medida' => 'Metro', 'factor_conversion' => 1],
                    ['color' => 'Beige',      'grosor' => '0.5 mm', 'marca' => 'Fabricato', 'precio' => 42.00, 'stock' => 28, 'unidad_medida' => 'Metro', 'factor_conversion' => 1, 'en_oferta' => true, 'descuento_porcentaje' => 5],
                ],
            ],
            [
                'nombre'      => 'Tela Denim Premium',
                'descripcion' => 'Denim de alta resistencia, 12 oz. Para confección de jeans, bolsos y accesorios.',
                'categoria'   => 'tela',
                'variantes'   => [
                    ['color' => 'Azul Claro', 'grosor' => '1.2 mm', 'marca' => 'Denim Co',  'precio' => 68.00, 'stock' => 22, 'unidad_medida' => 'Metro', 'factor_conversion' => 1],
                    ['color' => 'Azul Oscuro', 'grosor' => '1.2 mm', 'marca' => 'Denim Co', 'precio' => 68.00, 'stock' => 18, 'unidad_medida' => 'Metro', 'factor_conversion' => 1],
                    ['color' => 'Negro',      'grosor' => '1.2 mm', 'marca' => 'Denim Co',  'precio' => 68.00, 'stock' => 20, 'unidad_medida' => 'Metro', 'factor_conversion' => 1, 'en_oferta' => true, 'descuento_porcentaje' => 20],
                ],
            ],
            [
                'nombre'      => 'Kit Agujas de Crochet',
                'descripcion' => 'Set completo de 12 agujas de crochet en aluminio, con mangos ergonómicos.',
                'categoria'   => 'accesorios',
                'variantes'   => [
                    ['color' => 'Multicolor', 'grosor' => null,     'marca' => 'Clover',     'precio' => 55.00, 'stock' => 10, 'unidad_medida' => 'Kit', 'factor_conversion' => 1],
                    ['color' => 'Plateado',   'grosor' => null,     'marca' => 'Knit Pro',   'precio' => 75.00, 'stock' => 8,  'unidad_medida' => 'Kit', 'factor_conversion' => 1, 'en_oferta' => true, 'descuento_porcentaje' => 10],
                ],
            ],
            [
                'nombre'      => 'Botones de Nácar',
                'descripcion' => 'Botones decorativos de nácar natural, acabado brillante. Para manualidades.',
                'categoria'   => 'botones',
                'variantes'   => [
                    ['color' => 'Blanco',     'grosor' => null,     'marca' => 'Prym',       'precio' => 8.50,  'stock' => 200, 'unidad_medida' => 'Unidad', 'factor_conversion' => 1, 'en_oferta' => true, 'descuento_porcentaje' => 20],
                    ['color' => 'Negro',      'grosor' => null,     'marca' => 'Prym',       'precio' => 9.50,  'stock' => 180, 'unidad_medida' => 'Unidad', 'factor_conversion' => 1],
                ],
            ],
            [
                'nombre'      => 'Aros para Bordado',
                'descripcion' => 'Aros de madera para tensar la tela. Ideal para todo tipo de bordado.',
                'categoria'   => 'accesorios',
                'variantes'   => [
                    ['color' => 'Madera', 'grosor' => null, 'marca' => 'DMC', 'precio' => 15.00, 'stock' => 50, 'unidad_medida' => 'Set de 3', 'factor_conversion' => 1],
                    ['color' => 'Madera Oscura', 'grosor' => null, 'marca' => 'DMC', 'precio' => 16.50, 'stock' => 30, 'unidad_medida' => 'Set de 3', 'factor_conversion' => 1],
                ],
            ],
            [
                'nombre'      => 'Lana Merino Extra Suave',
                'descripcion' => 'Lana 100% merino, perfecta para tejer suéteres, bufandas y accesorios para el invierno.',
                'categoria'   => 'lana',
                'variantes'   => [
                    ['color' => 'Mostaza', 'grosor' => 'Medio', 'marca' => 'Lanas Alpina', 'precio' => 22.00, 'stock' => 120, 'unidad_medida' => 'Madeja 100g', 'factor_conversion' => 1, 'en_oferta' => true, 'descuento_porcentaje' => 15],
                    ['color' => 'Gris Jaspeado', 'grosor' => 'Medio', 'marca' => 'Lanas Alpina', 'precio' => 22.00, 'stock' => 85, 'unidad_medida' => 'Madeja 100g', 'factor_conversion' => 1],
                ],
            ],
            [
                'nombre'      => 'Kit de Bordado Principiantes',
                'descripcion' => 'Kit que incluye hilos, agujas, tela pre-impresa y aro. Todo para iniciar.',
                'categoria'   => 'accesorios',
                'variantes'   => [
                    ['color' => 'Multicolor', 'grosor' => null, 'marca' => 'Stitch & Co', 'precio' => 35.00, 'stock' => 40, 'unidad_medida' => 'Kit Completo', 'factor_conversion' => 1],
                ],
            ],
            [
                'nombre'      => 'Set de Pintura Textil',
                'descripcion' => 'Pinturas acrílicas para crear arte directamente en tus prendas.',
                'categoria'   => 'accesorios',
                'variantes'   => [
                    ['color' => '12 Colores', 'grosor' => null, 'marca' => 'Acrilex', 'precio' => 18.50, 'stock' => 60, 'unidad_medida' => 'Caja', 'factor_conversion' => 1],
                ],
            ],
            [
                'nombre'      => 'Tela Polar Suave',
                'descripcion' => 'Ideal para frazadas y ropa de invierno cálida.',
                'categoria'   => 'tela',
                'variantes'   => [
                    ['color' => 'Rosa Pastel', 'grosor' => 'Grueso', 'marca' => 'Polar', 'precio' => 28.00, 'stock' => 45, 'unidad_medida' => 'Metro', 'factor_conversion' => 1],
                    ['color' => 'Azul Marino', 'grosor' => 'Grueso', 'marca' => 'Polar', 'precio' => 28.00, 'stock' => 50, 'unidad_medida' => 'Metro', 'factor_conversion' => 1],
                ],
            ]
        ];

        foreach ($catalogo as $item) {
            $cat = Categoria::firstOrCreate(['nombre' => $item['categoria']]);

            $producto = Producto::create([
                'categoria_id' => $cat->id,
                'nombre'       => $item['nombre'],
                'descripcion'  => $item['descripcion'],
            ]);

            foreach ($item['variantes'] as $v) {
                // Seleccionamos un proveedor aleatorio de los creados
                $proveedorId = null;
                if (!empty($proveedoresIds)) {
                    $proveedorId = $proveedoresIds[array_rand($proveedoresIds)];
                }

                ProductoVariante::create([
                    'producto_id'          => $producto->id,
                    'parent_id'            => null,
                    'unidad_medida'        => $v['unidad_medida'],
                    'factor_conversion'    => $v['factor_conversion'],
                    'color'                => $v['color']               ?? null,
                    'grosor'               => $v['grosor']              ?? null,
                    'marca'                => $v['marca']               ?? null,
                    'precio'               => $v['precio'],
                    'stock_base'           => $v['stock'],
                    'proveedor_id'         => $proveedorId,
                    'en_oferta'            => $v['en_oferta']           ?? false,
                    'descuento_porcentaje' => $v['descuento_porcentaje'] ?? 0,
                ]);
            }
        }
    }
}
