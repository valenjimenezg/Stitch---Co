<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoVariante;
use Illuminate\Support\Str;

class OrganizarEtiquetas extends Command
{
    protected $signature = 'tienda:organizar';
    protected $description = 'Organiza las categorias y añade productos para Telas, Lanas, Tejidos, Costura, Manualidades';

    public function handle()
    {
        $sections = ['Telas', 'Lanas', 'Tejidos', 'Costura', 'Manualidades'];

        foreach ($sections as $sec) {
            $cat = Categoria::firstOrCreate(['nombre' => $sec], ['nombre' => $sec]);

            // What images belong to this section?
            $imgs = [];
            $titles = [];

            if ($sec === 'Telas') {
                $imgs = ['img/productos/1775513190_tela.jpg', 'img/productos/1775513250_tela1.jpg', 'img/productos/1775098026_denim.jpg', 'img/productos/1775097935_lino.webp'];
                $titles = ['Gasa Blanca', 'Tela de Algodón Estampada', 'Denim Clásico', 'Lino Natural'];
            } elseif ($sec === 'Lanas') {
                $imgs = ['img/productos/1775089039_hilo 100 algodon.png', 'img/productos/1775514114_rosa.jpg', 'img/productos/1775097676_mostaza.jpg'];
                $titles = ['Hilo 100% Algodón', 'Ovillo Rosa Premium', 'Lana Mostaza Gruesa'];
            } elseif ($sec === 'Tejidos') {
                $imgs = ['img/productos/1775514610_3.jpg', 'img/productos/1775514658_4.jpg', 'img/productos/1775514705_5.jpg', 'img/productos/1775512949_bambu.webp'];
                $titles = ['Set para Tejido Circulares', 'Agujas de Bambú para Tejer', 'Estuche de Tejedor', 'Agujas de Crochet Profesionales'];
            } elseif ($sec === 'Costura') {
                $imgs = ['img/productos/1774760641_tijeras.jpg', 'img/productos/1775094739_cremallera.jpg', 'img/productos/1775513107_b.jpg', 'img/productos/1775094807_blanco.jpg'];
                $titles = ['Tijeras de Costura Doradas', 'Cremallera Reforzada', 'Caja de Botones Nácar', 'Botón Clásico Blanco'];
            } elseif ($sec === 'Manualidades') {
                $imgs = ['img/productos/1775513635_kit.jpg', 'img/productos/1775514209_kit1.webp', 'img/productos/1775514969_kit4.jpg', 'img/productos/1775515021_ki5.jpg'];
                $titles = ['Kit de Manualidades Básico', 'Caja Organizadora de Hilos', 'Alfíleres Manuales', 'Kit Completo Creativo'];
            }

            // Create 4 products for each category
            for ($i = 0; $i < 4; $i++) {
                $prod = Producto::create([
                    'nombre' => $titles[$i % count($titles)] . ' - ' . rand(1, 100),
                    'descripcion' => 'Insumo de altisima calidad para tus proyectos de ' . $sec,
                    'categoria_id' => $cat->id,
                    'proveedor_id' => 1,
                    'sku' => strtoupper(substr($sec, 0, 3)) . '-' . rand(1000, 9999),
                    'activo' => true
                ]);

                ProductoVariante::create([
                    'producto_id' => $prod->id,
                    'proveedor_id' => 1,
                    'color' => 'Único',
                    'stock_base' => rand(15, 60),
                    'precio' => rand(200, 1500) / 100, // 2 to 15 usd
                    'imagen' => $imgs[$i % count($imgs)],
                    'en_oferta' => (rand(1, 10) > 7), // ~30% are on offer
                    'descuento_porcentaje' => rand(10, 30)
                ]);
            }
        }
        $this->info("Hecho! Todos tienen productos reales!!");
    }
}
