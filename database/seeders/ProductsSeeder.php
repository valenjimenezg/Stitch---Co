<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPresentation;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProductPresentation::truncate();
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $cats = Category::pluck('id', 'name');

        $productos = [
            // BOTONES
            ['category' => 'Botones', 'name' => 'Botones de Nácar Redondos',    'desc' => 'Botones de nácar natural, acabado perlado, 4 agujeros.',       'stock' => 500,  'grosor' => null, 'color' => 'Blanco Perlado', 'marca' => 'GenéricoPremium', 'cm' => 1.5,  'unidad' => 'Ninguna', 'precio' => 0.30],
            ['category' => 'Botones', 'name' => 'Botones de Nácar Ovalados',    'desc' => 'Botones ovalados de nácar, tamaño mediano.',                     'stock' => 300,  'grosor' => null, 'color' => 'Crema',           'marca' => 'GenéricoPremium', 'cm' => 2.0,  'unidad' => 'Ninguna', 'precio' => 0.45],
            ['category' => 'Botones', 'name' => 'Botones Metálicos Dorados',    'desc' => 'Botones de metal bañados en oro, ideales para sacos y abrigos.', 'stock' => 200,  'grosor' => null, 'color' => 'Dorado',          'marca' => 'MetalCraft',      'cm' => 2.5,  'unidad' => 'Ninguna', 'precio' => 0.65],
            ['category' => 'Botones', 'name' => 'Botones de Madera Natural',    'desc' => 'Botones rústicos de madera, 2 agujeros.',                        'stock' => 150,  'grosor' => null, 'color' => 'Natural',         'marca' => 'EcoSew',          'cm' => 2.0,  'unidad' => 'Ninguna', 'precio' => 0.20],
            // HILOS
            ['category' => 'Hilos',   'name' => 'Hilo de Algodón Mercerizado',  'desc' => 'Hilo de algodón 100% mercerizado, ideal para bordado y costura.', 'stock' => 200, 'grosor' => 'Fino',   'color' => 'Blanco',  'marca' => 'Coats',      'cm' => null, 'unidad' => 'Metros', 'precio' => 1.50],
            ['category' => 'Hilos',   'name' => 'Hilo de Seda Natural Rojo',    'desc' => 'Hilo de seda pura, colores vibrantes para bordado fino.',         'stock' => 80,  'grosor' => 'Muy Fino', 'color' => 'Rojo',   'marca' => 'SilkLine',   'cm' => null, 'unidad' => 'Metros', 'precio' => 3.80],
            ['category' => 'Hilos',   'name' => 'Hilo Elástico Transparente',   'desc' => 'Hilo elástico invisible para bisutería y pulseras.',              'stock' => 150, 'grosor' => 'Fino',   'color' => 'Transparente', 'marca' => 'ElastiCraft', 'cm' => null, 'unidad' => 'Metros', 'precio' => 0.90],
            // LANAS
            ['category' => 'Lanas',   'name' => 'Madeja de Lana Merino Azul',   'desc' => 'Lana Merino 100%, suave al tacto, ideal para tejido.',            'stock' => 60,  'grosor' => 'Grueso', 'color' => 'Azul Marino', 'marca' => 'LionBrand', 'cm' => null, 'unidad' => 'Gramos', 'precio' => 4.50],
            ['category' => 'Lanas',   'name' => 'Madeja de Lana Acrílica Rosa', 'desc' => 'Lana acrílica lavable, colores pastel, ideal para amigurumi.',   'stock' => 120, 'grosor' => 'Medio',  'color' => 'Rosa',       'marca' => 'CraftYarn', 'cm' => null, 'unidad' => 'Gramos', 'precio' => 2.20],
            // TELAS
            ['category' => 'Telas',   'name' => 'Tela Popelina Estampada',      'desc' => 'Popelina 100% algodón estampada, ancho 1.40m.',                  'stock' => 100, 'grosor' => null, 'color' => 'Multicolor', 'marca' => 'TelasVenecia', 'cm' => 140, 'unidad' => 'Metros', 'precio' => 3.00],
            ['category' => 'Telas',   'name' => 'Tela Licra Deportiva Negra',   'desc' => 'Licra de 4 vías, perfecta para ropa deportiva y trajes de baño.', 'stock' => 50,  'grosor' => null, 'color' => 'Negro',       'marca' => 'SportFlex',    'cm' => 150, 'unidad' => 'Metros', 'precio' => 5.50],
            // CIERRES
            ['category' => 'Cierres', 'name' => 'Cierre Metálico Plateado 20cm','desc' => 'Cierre YKK metálico, plateado, longitud 20cm.',                 'stock' => 200, 'grosor' => null, 'color' => 'Plateado', 'marca' => 'YKK',       'cm' => 20,  'unidad' => 'Ninguna', 'precio' => 0.80],
            ['category' => 'Cierres', 'name' => 'Cierre de Nylon Invisible 30cm','desc' => 'Cierre invisible de nylon, ideal para vestidos y faldas.',      'stock' => 150, 'grosor' => null, 'color' => 'Blanco',   'marca' => 'YKK',       'cm' => 30,  'unidad' => 'Ninguna', 'precio' => 0.60],
            // AGUJAS
            ['category' => 'Agujas',  'name' => 'Agujas de Coser Surtidas',     'desc' => 'Set de agujas para máquina de coser, tallas 75/11 a 100/16.',    'stock' => 300, 'grosor' => null, 'color' => null, 'marca' => 'Schmetz', 'cm' => null, 'unidad' => 'Ninguna', 'precio' => 2.50],
            ['category' => 'Agujas',  'name' => 'Agujas de Tejer Bambú N° 5',   'desc' => 'Agujas de bambu para tejido en par, N° 5, longitud 35cm.',       'stock' => 80,  'grosor' => null, 'color' => null, 'marca' => 'ChiaoGoo','cm' => 35,  'unidad' => 'Ninguna', 'precio' => 3.20],
            // CINTAS
            ['category' => 'Cintas',  'name' => 'Cinta Satinada Blanca 2cm',    'desc' => 'Cinta satinada de doble cara, ancho 2cm, rollo 25 metros.',      'stock' => 50,  'grosor' => null, 'color' => 'Blanco',  'marca' => 'Offray',    'cm' => 200, 'unidad' => 'Metros', 'precio' => 1.20],
            ['category' => 'Cintas',  'name' => 'Cinta de Grograin Rosa 3cm',   'desc' => 'Cinta de grograin texturada, ancho 3cm, rollo 10 metros.',       'stock' => 40,  'grosor' => null, 'color' => 'Rosa',    'marca' => 'Offray',    'cm' => 300, 'unidad' => 'Metros', 'precio' => 0.90],
            // ELÁSTICOS
            ['category' => 'Elásticos','name' => 'Elástico Plano Blanco 2cm',   'desc' => 'Elástico plano de alta elasticidad, ancho 2cm.',                 'stock' => 100, 'grosor' => null, 'color' => 'Blanco',  'marca' => 'ElastiMax', 'cm' => 200, 'unidad' => 'Metros', 'precio' => 0.50],
            // ENCAJES
            ['category' => 'Encajes', 'name' => 'Encaje de Algodón Blanco 5cm', 'desc' => 'Encaje bordado en algodón, ancho 5cm, diseño floral.',           'stock' => 60,  'grosor' => null, 'color' => 'Blanco',  'marca' => 'LaceCraft', 'cm' => 500, 'unidad' => 'Metros', 'precio' => 1.80],
        ];

        foreach ($productos as $p) {
            $catId = $cats[$p['category']] ?? null;
            if (!$catId) continue;

            DB::transaction(function() use ($p, $catId) {
                $product = Product::create([
                    'category_id'     => $catId,
                    'name'            => $p['name'],
                    'description'     => $p['desc'],
                    'stock_total_base'=> $p['stock'],
                    'grosor'          => $p['grosor'],
                    'color'           => $p['color'],
                    'marca'           => $p['marca'],
                    'cm'              => $p['cm'],
                    'unidad_medida'   => $p['unidad'],
                    'en_oferta'       => 0,
                    'descuento_porcentaje' => 0,
                ]);

                // Presentación base: Unidad
                $product->presentations()->create([
                    'name'              => 'Unidad',
                    'conversion_factor' => 1,
                    'price'             => $p['precio'],
                ]);
            });
        }

        echo "Productos creados: " . Product::count() . "\n";
    }
}
