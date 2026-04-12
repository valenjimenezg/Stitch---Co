<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$v1 = \App\Models\ProductoVariante::find(1); // Rojo
$v2 = \App\Models\ProductoVariante::find(2); // Azul
$v3 = \App\Models\ProductoVariante::find(3); // Blanco

if ($v1) {
    $v1->imagen = 'img/productos/1774760434_Sb9x8YWkSiQnfqfuTHvtGaThZhiq6OYbnrCK9fxk.webp';
    $v1->save();
}

if ($v2) {
    $v2->imagen = 'img/productos/1774760451_tmLd5qEQ8AazwtckJX92bSal7yoSbHQm4V4CYuGn.webp';
    $v2->save();
}

if ($v3) {
    $v3->imagen = 'img/productos/1774760462_LaB27T0SBsDkg1xVzpOrzQ27HeRnK3Zz4gq2a8x8.webp'; // or hilo 100 algodon.png
    $v3->save();
}

echo "Assigned correct images based on RGB color analysis.\n";
