<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Rename Cierre
$p = App\Models\Producto::find(11);
$p->nombre = 'Cierre';
$p->save();

// 2. Rename Categories
$c1 = App\Models\Categoria::find(1); $c1->nombre = 'tejido'; $c1->save();
$c2 = App\Models\Categoria::find(2); $c2->nombre = 'tela'; $c2->save();
$c3 = App\Models\Categoria::find(3); $c3->nombre = 'costura'; $c3->save();
$c4 = App\Models\Categoria::find(4); $c4->nombre = 'manualidades'; $c4->save();

// 3. Move products to right categories
App\Models\Producto::where('id', 1)->update(['categoria_id' => 3]); // Hilo -> Costura
App\Models\Producto::where('id', 5)->update(['categoria_id' => 3]); // Botones -> Costura
App\Models\Producto::where('id', 6)->update(['categoria_id' => 4]); // Aros -> Manualidades
App\Models\Producto::where('id', 8)->update(['categoria_id' => 4]); // Kit bordado -> Manualidades
App\Models\Producto::where('id', 9)->update(['categoria_id' => 4]); // Pintura -> Manualidades

// 4. Update Hilo variants (Product 1)
$v_rojo = App\Models\ProductoVariante::where('producto_id', 1)->where('color', 'Rojo')->first();
if ($v_rojo) {
    $v_rojo->imagen = 'img/productos/1774760451_tmLd5qEQ8AazwtckJX92bSal7yoSbHQm4V4CYuGn.webp';
    $v_rojo->save();
}
$v_azul = App\Models\ProductoVariante::where('producto_id', 1)->where('color', 'Azul')->first();
if ($v_azul) {
    $v_azul->imagen = 'img/productos/1774760462_LaB27T0SBsDkg1xVzpOrzQ27HeRnK3Zz4gq2a8x8.webp';
    $v_azul->save();
}

echo "Database updated!";
