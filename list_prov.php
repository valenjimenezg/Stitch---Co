<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$px = \App\Models\Proveedor::all();
foreach($px as $p) {
    echo $p->id . " - " . $p->nombre . " - " . ($p->contacto ?? 'none') . "\n";
}
