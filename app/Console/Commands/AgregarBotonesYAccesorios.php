<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoVariante;

class AgregarBotonesYAccesorios extends Command
{
    protected $signature = 'tienda:botones_accesorios';
    protected $description = 'Agrega categorias y productos para Botones y Accesorios';

    public function handle()
    {
        $sections = ['Botones', 'Accesorios'];

        foreach ($sections as $sec) {
            $cat = Categoria::firstOrCreate(['nombre' => $sec], ['nombre' => $sec]);

            $imgs = [];
            $titles = [];

            if ($sec === 'Botones') {
                $imgs = ['img/productos/1775513107_b.jpg', 'img/productos/1775094807_blanco.jpg', 'img/productos/1775098026_denim.jpg', 'img/productos/1775098026_denim.jpg'];
                $titles = ['Caja de Botones Nácar', 'Botón Clásico Blanco', 'Botón de Madera', 'Botones Vintage'];
            } elseif ($sec === 'Accesorios') {
                $imgs = ['img/productos/1774760641_tijeras.jpg', 'img/productos/1775094739_cremallera.jpg', 'img/productos/1775513635_kit.jpg', 'img/productos/1775514209_kit1.webp'];
                $titles = ['Tijeras Profesionales', 'Cremallera Reforzada', 'Set de Hilos', 'Caja Organizadora'];
            }

            for ($i = 0; $i < 4; $i++) {
                $prod = Producto::create([
                    'nombre' => $titles[$i % count($titles)] . ' - ' . rand(1, 100),
                    'descripcion' => 'Insumo premium para ' . $sec,
                    'categoria_id' => $cat->id,
                    'proveedor_id' => 1,
                    'sku' => strtoupper(substr($sec, 0, 3)) . '-' . rand(1000, 9999),
                    'activo' => true
                ]);

                ProductoVariante::create([
                    'producto_id' => $prod->id,
                    'proveedor_id' => 1,
                    'color' => 'Único',
                    'stock_base' => rand(10, 50),
                    'precio' => rand(200, 1500) / 100,
                    'imagen' => $imgs[$i % count($imgs)],
                    'en_oferta' => (rand(1, 10) > 7),
                    'descuento_porcentaje' => rand(10, 30)
                ]);
            }
        }
        $this->info("Botones y Accesorios agregados!!");
    }
}
