<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$px = \App\Models\Proveedor::where('nombre', 'LIKE', '%Delcy%')->orWhere('nombre', 'LIKE', '%Rodriguez%')->get();
foreach($px as $p) {
    $p->nombre = 'Distribuidora Fenix (Avatar)';
    $p->save();
    echo "Proveedor ".$p->id." actualizado correctamente.\n";
}
if($px->isEmpty()){
    echo "Proveedor no encontrado.\n";
}
