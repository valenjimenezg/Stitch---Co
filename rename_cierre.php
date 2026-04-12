<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Producto::find(11);
if($p) {
    $p->nombre = 'Cierre de Costura';
    $p->save();
    echo "Product renamed to 'Cierre de Costura'.";
} else {
    echo "Product not found.";
}
