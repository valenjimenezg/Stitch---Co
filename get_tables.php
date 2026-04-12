<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['users', 'proveedores', 'categorias', 'productos', 'detalle_productos', 'carritos', 'ventas', 'direcciones', 'detalle_carritos', 'detalle_ventas', 'lista_deseos', 'notificaciones_stock', 'subscribers', 'products', 'product_presentations', 'empaques_producto', 'movimiento_inventarios', 'notificaciones_crm', 'inventario_logs'];
$res = [];
foreach($tables as $t){
    if(\Illuminate\Support\Facades\Schema::hasTable($t)) {
        $res[$t] = \Illuminate\Support\Facades\Schema::getColumnListing($t);
    }
}
echo json_encode($res, JSON_PRETTY_PRINT);
