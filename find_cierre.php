<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$productos = \App\Models\Producto::where('nombre', 'like', '%Cierre%')
    ->orWhere('nombre', 'like', '%Cremallera%')
    ->get();

foreach ($productos as $p) {
    echo "ID: $p->id | Nombre: $p->nombre\n";
}
