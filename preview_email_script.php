<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Tomar la variante de Tijeras de Costura
$variante = App\Models\DetalleProducto::whereHas('producto', function($q) {
    $q->where('nombre', 'like', '%Tijeras de Costura%');
})->where('color', 'like', '%Negro%')->first();

if (!$variante) {
    $variante = App\Models\DetalleProducto::whereNotNull('imagen')->first();
}

$mailable = new App\Mail\BackInStockMail($variante);
$html = $mailable->render();

// Forzar Base64 para la imagen para que el navegador Chrome pueda verla sin bloqueos de "file:///"
if ($variante && $variante->imagen) {
    $path = public_path($variante->imagen);
    if(file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $html = str_replace(url($variante->imagen), $base64, $html);
    }
}

// Guardar en public/preview.html
file_put_contents(public_path('preview.html'), $html);

echo "Preview generado en public/preview.html\n";
