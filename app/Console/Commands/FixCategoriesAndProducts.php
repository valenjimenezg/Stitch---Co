<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoVariante;

class FixCategoriesAndProducts extends Command
{
    protected $signature = 'fix:categories';
    protected $description = 'Fix categories to Kits, Lanas, Telas, Botones and populate with dummy products where empty';

    public function handle()
    {
        // 1. Rename "Accesorios" to "Kits" so route('categories.show', 'kits') works
        $catAccesorios = Categoria::where('nombre', 'Accesorios')->first();
        if ($catAccesorios) {
            $catAccesorios->nombre = 'Kits';
            $catAccesorios->save();
        }

        // 2. Ensure each category has at least 3 products.
        $categories = Categoria::all();
        $images = glob('public/img/productos/*.{jpg,jpeg,png,webp}', GLOB_BRACE);

        foreach ($categories as $cat) {
            $count = Producto::where('categoria_id', $cat->id)->count();

            if ($count < 3) {
                // Determine some base images based on category name
                $catName = strtolower($cat->nombre);
                $catImages = [];
                
                foreach ($images as $img) {
                    $imgName = basename($img);
                    if (str_contains(strtolower($imgName), $catName === 'kits' ? 'kit' : substr($catName, 0, 4))) {
                        $catImages[] = str_replace('public/', '', $img);
                    }
                }
                
                // Fallback to random images
                if (empty($catImages)) {
                    $catImages = collect($images)->random(3)->map(fn($img) => str_replace('public/', '', $img))->toArray();
                }

                $needed = 3 - $count;
                for ($i = 0; $i < $needed; $i++) {
                    $prod = Producto::create([
                        'nombre' => 'Producto Genérico de ' . $cat->nombre . ' ' . rand(100, 999),
                        'descripcion' => 'Descripción autogenerada para rellenar la categoría.',
                        'categoria_id' => $cat->id,
                        'proveedor_id' => 1,
                        'sku' => strtoupper(substr($cat->nombre, 0, 3)) . '-' . rand(1000, 9999),
                        'activo' => true
                    ]);

                    $imgPath = $catImages[array_rand($catImages)];

                    // Add variante so it shows up in storefront
                    ProductoVariante::create([
                        'producto_id' => $prod->id,
                        'proveedor_id' => 1,
                        'color' => 'Estándar',
                        'stock_base' => rand(10, 50),
                        'precio' => rand(150, 600) / 100,
                        'imagen' => $imgPath,
                        'en_oferta' => (rand(1, 10) > 7),
                        'descuento_porcentaje' => rand(5, 20)
                    ]);
                }
            }
        }
        $this->info("¡Categorías ajustadas y productos rellenados!");
    }
}
