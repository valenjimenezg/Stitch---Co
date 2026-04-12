<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$variantes = App\Models\ProductoVariante::all();
$files = glob(public_path('img/productos/*.*'));

if (empty($files)) {
    echo "No files found\n";
    exit;
}

foreach ($variantes as $v) {
    if (!$v->imagen) {
        $randomFile = $files[array_rand($files)];
        $relativePath = 'img/productos/' . basename($randomFile);
        $v->imagen = $relativePath;
        $v->save();
        echo "Assigned image to variant {$v->id} -> $relativePath\n";
    }
}
echo "Done!\n";
