<?php
$productos = \App\Models\Producto::with('detalles')->get();

foreach ($productos as $producto) {
    if ($producto->detalles->isEmpty()) continue;

    switch (strtolower($producto->categoria)) {
        case 'telas':
            $base_price = mt_rand(350, 1200) / 100;
            break;
        case 'costura':
            $base_price = mt_rand(100, 800) / 100;
            break;
        case 'tejido':
            $base_price = mt_rand(250, 600) / 100;
            break;
        case 'accesorios':
        case 'merceria':
        default:
            $base_price = mt_rand(50, 400) / 100;
            break;
    }

    foreach ($producto->detalles as $detalle) {
        $precio = $base_price + (mt_rand(0, 100) / 100);
        $detalle->precio = $precio;
        $detalle->precio_con_descuento = round($precio * 0.8, 2);
        $detalle->save();
    }
}
echo "Done.\n";
