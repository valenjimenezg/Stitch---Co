<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$prod = App\Models\Producto::create([
    'nombre' => 'Cierre / Cremallera de Costura',
    'descripcion' => 'Cremallera de alta calidad y resistencia para todo tipo de prendas y manualidades.',
    'categoria_id' => 3, // accesorios
    'sku' => 'ACC-CIE-001'
]);

// Variant 1: Azul
App\Models\ProductoVariante::create([
    'producto_id' => $prod->id,
    'color' => 'Azul',
    'precio' => 1.50,
    'precio_usd' => 1.50,
    'sku' => 'ACC-CIE-001-AZU',
    'imagen' => 'img/productos/1775098523_cremalleraazul.jpg',
    'stock_base' => 100,
    'factor_conversion' => 1
]);

// Variant 2: Metálico/Oscuro (cremallera.jpg)
App\Models\ProductoVariante::create([
    'producto_id' => $prod->id,
    'color' => 'Metálico Oscuro',
    'precio' => 1.50,
    'precio_usd' => 1.50,
    'sku' => 'ACC-CIE-001-MET',
    'imagen' => 'img/productos/1775094739_cremallera.jpg',
    'stock_base' => 100,
    'factor_conversion' => 1
]);

echo "Cierres creados.\n";
