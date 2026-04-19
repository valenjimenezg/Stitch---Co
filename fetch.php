<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$empaques = \App\Models\ProductoVariante::whereNotNull('parent_id')->orderBy('id', 'desc')->take(5)->get();
foreach($empaques as $e) {
    echo "ID " . $e->id . " | Nombre: " . $e->unidad_medida . " | precio: " . $e->precio . " | precio_usd: " . $e->precio_usd . "\n";
}
