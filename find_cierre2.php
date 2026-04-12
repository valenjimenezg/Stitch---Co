<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$productos = DB::table('productos')->get();
foreach ($productos as $p) {
    if (strpos($p->nombre, 'Cierre') !== false || strpos($p->nombre, 'Cremallera') !== false) {
        echo "ID: $p->id | Nombre: $p->nombre\n";
    }
}
