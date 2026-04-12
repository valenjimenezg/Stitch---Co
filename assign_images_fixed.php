<?php

$map = [
    // Hilo
    1 => 'img/productos/hilo 100 algodon.png',
    2 => 'img/productos/hilo 100 algodon.png',
    3 => 'img/productos/hilo 100 algodon.png',

    // Lino
    4 => 'img/productos/1775097924_naturkalino.jpg',
    5 => 'img/productos/1775097935_lino.webp',
    6 => 'img/productos/1775097975_beige.jpg',

    // Denim
    7 => 'img/productos/1775098026_denim.jpg',
    8 => 'img/productos/1775098104_oscuro.webp',
    9 => 'img/productos/1775098277_negrogim.webp',

    // Agujas
    10 => 'img/productos/1775514269_kit2.webp',
    11 => 'img/productos/1775514209_kit1.webp',

    // Botones
    12 => 'img/productos/1775094856_marfil.jpg',
    13 => 'img/productos/1775095067_negro.webp',

    // Aros
    14 => 'img/productos/1775512949_bambu.webp',
    15 => 'img/productos/1775513056_ba,bu2.jpg',

    // Lana
    16 => 'img/productos/1775097676_mostaza.jpg',
    17 => 'img/productos/1775514162_grisp.png',

    // Kit de bordado
    18 => 'img/productos/1775513635_kit.jpg',

    // Pintura
    19 => 'img/productos/1775514610_3.jpg',

    // Polar
    20 => 'img/productos/1775514114_rosa.jpg',
    21 => 'img/productos/1775514075_polar.jpg',
];

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach ($map as $id => $path) {
    $v = App\Models\ProductoVariante::find($id);
    if ($v) {
        $v->imagen = $path;
        $v->save();
        echo "Updated Variant $id with $path\n";
    }
}
