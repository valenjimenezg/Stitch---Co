<?php

namespace Database\Seeders;

use App\Models\DetalleProducto;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $catalogo = [
            [
                'nombre'      => 'Hilo de Algodón 100%',
                'descripcion' => 'Hilo suave y resistente, ideal para tejido a crochet y bordado. 100% algodón peinado.',
                'categoria'   => 'hilos',
                'variantes'   => [
                    ['color' => 'Blanco',     'grosor' => '4 mm',  'marca' => 'Coats',      'cm' => 200, 'precio' => 15.50, 'stock' => 30],
                    ['color' => 'Rojo',       'grosor' => '4 mm',  'marca' => 'Coats',      'cm' => 200, 'precio' => 15.50, 'stock' => 20, 'en_oferta' => true, 'descuento_porcentaje' => 10],
                    ['color' => 'Azul Marino','grosor' => '4 mm',  'marca' => 'Coats',      'cm' => 200, 'precio' => 15.50, 'stock' => 25],
                ],
            ],
            [
                'nombre'      => 'Lana Merino Premium',
                'descripcion' => 'Lana de alta calidad, extra suave al tacto. Perfecta para tejido con agujas y crochet.',
                'categoria'   => 'lanas',
                'variantes'   => [
                    ['color' => 'Crema',      'grosor' => '6 mm',  'marca' => 'Lion Brand', 'cm' => 100, 'precio' => 28.00, 'stock' => 15, 'en_oferta' => true, 'descuento_porcentaje' => 15],
                    ['color' => 'Gris Perla', 'grosor' => '6 mm',  'marca' => 'Lion Brand', 'cm' => 100, 'precio' => 28.00, 'stock' => 18],
                    ['color' => 'Mostaza',    'grosor' => '6 mm',  'marca' => 'Lion Brand', 'cm' => 100, 'precio' => 28.00, 'stock' => 12],
                ],
            ],
            [
                'nombre'      => 'Tela de Lino Natural',
                'descripcion' => 'Tela de lino 100% natural, transpirable y duradera. Ideal para confección de ropa y manualidades.',
                'categoria'   => 'telas',
                'variantes'   => [
                    ['color' => 'Natural',    'grosor' => '0.5 mm', 'marca' => 'Fabricato',  'cm' => 150, 'precio' => 45.00, 'stock' => 40],
                    ['color' => 'Blanco',     'grosor' => '0.5 mm', 'marca' => 'Fabricato',  'cm' => 150, 'precio' => 45.00, 'stock' => 35],
                    ['color' => 'Beige',      'grosor' => '0.5 mm', 'marca' => 'Fabricato',  'cm' => 150, 'precio' => 42.00, 'stock' => 28, 'en_oferta' => true, 'descuento_porcentaje' => 5],
                ],
            ],
            [
                'nombre'      => 'Tela Denim Premium',
                'descripcion' => 'Denim de alta resistencia, 12 oz. Para confección de jeans, bolsos y accesorios de moda.',
                'categoria'   => 'telas',
                'variantes'   => [
                    ['color' => 'Azul Claro', 'grosor' => '1.2 mm', 'marca' => 'Denim Co',   'cm' => 140, 'precio' => 68.00, 'stock' => 22],
                    ['color' => 'Azul Oscuro','grosor' => '1.2 mm', 'marca' => 'Denim Co',   'cm' => 140, 'precio' => 68.00, 'stock' => 18],
                    ['color' => 'Negro',      'grosor' => '1.2 mm', 'marca' => 'Denim Co',   'cm' => 140, 'precio' => 68.00, 'stock' => 20, 'en_oferta' => true, 'descuento_porcentaje' => 20],
                ],
            ],
            [
                'nombre'      => 'Kit Agujas de Crochet',
                'descripcion' => 'Set completo de 12 agujas de crochet en aluminio, con mangos ergonómicos antideslizantes.',
                'categoria'   => 'herramientas',
                'variantes'   => [
                    ['color' => 'Multicolor', 'grosor' => null,     'marca' => 'Clover',     'cm' => null, 'precio' => 55.00, 'stock' => 10],
                    ['color' => 'Plateado',   'grosor' => null,     'marca' => 'Knit Pro',   'cm' => null, 'precio' => 75.00, 'stock' => 8, 'en_oferta' => true, 'descuento_porcentaje' => 10],
                ],
            ],
            [
                'nombre'      => 'Tijeras de Costura Profesional',
                'descripcion' => 'Tijeras de acero inoxidable para costura, con mango ergonómico. Corte preciso y duradero.',
                'categoria'   => 'herramientas',
                'variantes'   => [
                    ['color' => 'Plateado',   'grosor' => null,     'marca' => 'Fiskars',    'cm' => 21,  'precio' => 38.00, 'stock' => 25],
                    ['color' => 'Negro',      'grosor' => null,     'marca' => 'Fiskars',    'cm' => 25,  'precio' => 45.00, 'stock' => 15],
                ],
            ],
            [
                'nombre'      => 'Botones de Nácar',
                'descripcion' => 'Botones decorativos de nácar natural, acabado brillante. Para ropa, accesorios y manualidades.',
                'categoria'   => 'merceria',
                'variantes'   => [
                    ['color' => 'Blanco',     'grosor' => null,     'marca' => 'Prym',       'cm' => 1.5, 'precio' => 8.50,  'stock' => 200, 'en_oferta' => true, 'descuento_porcentaje' => 20],
                    ['color' => 'Marfil',     'grosor' => null,     'marca' => 'Prym',       'cm' => 1.5, 'precio' => 8.50,  'stock' => 150],
                    ['color' => 'Negro',      'grosor' => null,     'marca' => 'Prym',       'cm' => 2.0, 'precio' => 9.50,  'stock' => 180],
                ],
            ],
            [
                'nombre'      => 'Cremallera YKK Invisible',
                'descripcion' => 'Cremallera invisible de alta calidad, ideal para vestidos, faldas y blusas. Cierre suave.',
                'categoria'   => 'merceria',
                'variantes'   => [
                    ['color' => 'Blanco',     'grosor' => null,     'marca' => 'YKK',        'cm' => 22,  'precio' => 5.00,  'stock' => 100],
                    ['color' => 'Negro',      'grosor' => null,     'marca' => 'YKK',        'cm' => 22,  'precio' => 5.00,  'stock' => 120],
                    ['color' => 'Beige',      'grosor' => null,     'marca' => 'YKK',        'cm' => 22,  'precio' => 5.00,  'stock' => 80, 'en_oferta' => true, 'descuento_porcentaje' => 15],
                ],
            ],
            [
                'nombre'      => 'Agujas de Tejer Bambú',
                'descripcion' => 'Agujas de tejer de bambú natural, ligeras y cálidas al tacto. No se resbalan al trabajar.',
                'categoria'   => 'herramientas',
                'variantes'   => [
                    ['color' => 'Natural',    'grosor' => '3.5 mm', 'marca' => 'Seeknit',    'cm' => 35,  'precio' => 18.00, 'stock' => 30],
                    ['color' => 'Natural',    'grosor' => '5 mm',   'marca' => 'Seeknit',    'cm' => 35,  'precio' => 20.00, 'stock' => 25],
                    ['color' => 'Natural',    'grosor' => '8 mm',   'marca' => 'Seeknit',    'cm' => 35,  'precio' => 22.00, 'stock' => 20, 'en_oferta' => true, 'descuento_porcentaje' => 10],
                ],
            ],
            [
                'nombre'      => 'Entretela Termofusible',
                'descripcion' => 'Entretela adhesiva termofusible de doble cara, para reforzar telas y confeccionar bolsos.',
                'categoria'   => 'accesorios',
                'variantes'   => [
                    ['color' => 'Blanco',     'grosor' => '0.3 mm', 'marca' => 'Vlieseline',  'cm' => 90,  'precio' => 22.00, 'stock' => 50],
                    ['color' => 'Negro',      'grosor' => '0.3 mm', 'marca' => 'Vlieseline',  'cm' => 90,  'precio' => 22.00, 'stock' => 40, 'en_oferta' => true, 'descuento_porcentaje' => 10],
                ],
            ],
            [
                'nombre'      => 'Hilo de Seda Natural',
                'descripcion' => 'Hilo de seda 100% natural, luminoso y suave. Ideal para bordados y proyectos especiales.',
                'categoria'   => 'hilos',
                'variantes'   => [
                    ['color' => 'Dorado',     'grosor' => '2 mm',  'marca' => 'Anchor',     'cm' => 300, 'precio' => 35.00, 'stock' => 20, 'en_oferta' => true, 'descuento_porcentaje' => 25],
                    ['color' => 'Plateado',   'grosor' => '2 mm',  'marca' => 'Anchor',     'cm' => 300, 'precio' => 35.00, 'stock' => 15],
                    ['color' => 'Rosa Palo',  'grosor' => '2 mm',  'marca' => 'Anchor',     'cm' => 300, 'precio' => 35.00, 'stock' => 18],
                ],
            ],
            [
                'nombre'      => 'Tela Polar Suave',
                'descripcion' => 'Tela polar antifrizz, muy suave y abrigada. Para mantas, pijamas y ropa de invierno.',
                'categoria'   => 'telas',
                'variantes'   => [
                    ['color' => 'Azul',       'grosor' => '3 mm',  'marca' => 'Polartec',   'cm' => 150, 'precio' => 52.00, 'stock' => 30],
                    ['color' => 'Rosa',       'grosor' => '3 mm',  'marca' => 'Polartec',   'cm' => 150, 'precio' => 52.00, 'stock' => 25, 'en_oferta' => true, 'descuento_porcentaje' => 15],
                    ['color' => 'Gris',       'grosor' => '3 mm',  'marca' => 'Polartec',   'cm' => 150, 'precio' => 52.00, 'stock' => 20],
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
                    'en_oferta'            => $v['en_oferta']           ?? false,
                    'descuento_porcentaje' => $v['descuento_porcentaje'] ?? 0,
                ]);
            }
        }
    }
}
