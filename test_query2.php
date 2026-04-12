<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$variantes = \App\Models\ProductoVariante::with('producto')
    ->whereHas('producto', function($q) {
        $q->where('nombre', 'like', '%Hilo de Algodón Premium%');
    })
    ->get()
    ->toArray();

echo json_encode($variantes, JSON_PRETTY_PRINT);
