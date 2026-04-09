<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;
use App\Models\DetalleProducto;

$productos = Producto::with('detalles')->get();

foreach ($productos as $producto) {
    if ($producto->detalles->isEmpty()) continue;

    switch (strtolower($producto->categoria)) {
        case 'telas':
            $base_price = mt_rand(350, 1200) / 100; // 3.50 to 12.00
            break;
        case 'costura':
            $base_price = mt_rand(100, 800) / 100; // 1.00 to 8.00
            break;
        case 'tejido':
            $base_price = mt_rand(250, 600) / 100; // 2.50 to 6.00
            break;
        case 'accesorios':
        case 'merceria':
        default:
            $base_price = mt_rand(50, 400) / 100; // 0.50 to 4.00
            break;
    }

    foreach ($producto->detalles as $detalle) {
        $precio = $base_price + (mt_rand(0, 100) / 100); // little variation per variant
        $detalle->precio = $precio;
        $detalle->precio_con_descuento = $precio * 0.8; // 20% off if on sale
        $detalle->save();
    }
}
echo "Precios actualizados exitosamente.\n";
