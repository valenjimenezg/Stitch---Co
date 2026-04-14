<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Split Rosa/Gris variant
$variant = App\Models\ProductoVariante::find(109);
if ($variant && str_contains($variant->color, '/')) {
    // Break "Rosa/Gris" into "Rosa"
    $variant->color = 'Rosa';
    $variant->save();

    // Duplicate as "Gris"
    $newVariant = $variant->replicate();
    $newVariant->color = 'Gris';
    $newVariant->save();

    echo "¡Variantes separadas! Ahora el sistema reconocerá Rosa y Gris de forma independiente.\n";
} else {
    echo "No fue posible encontrar la variante 109 o ya estaba separada.\n";
}
