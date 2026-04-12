<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$v1 = \App\Models\ProductoVariante::find(1); // Rojo
$v2 = \App\Models\ProductoVariante::find(2); // Azul

if ($v1 && $v2) {
    $img1 = $v1->imagen;
    $img2 = $v2->imagen;
    
    // Swap images if v1 is supposed to be red but has the blue spool (1774760451 usually seems to be the wrong one)
    $v1->imagen = $img2;
    $v2->imagen = $img1;
    
    $v1->save();
    $v2->save();
    echo "Swapped images between variant 1 (Rojo) and 2 (Azul).";
} else {
    echo "Variants not found.";
}
