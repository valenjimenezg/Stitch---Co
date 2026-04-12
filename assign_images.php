<?php
$variantes = App\Models\ProductoVariante::with('producto')->get();
foreach ($variantes as $v) {
    echo $v->id . " | " . $v->producto->nombre . " | " . $v->color . "\n";
}
