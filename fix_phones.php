<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$provs = \App\Models\Proveedor::all();
$fakes = ['0000-0000001', '0000-0000002', '0000-0000003'];
foreach($provs as $key => $p) {
    if (isset($fakes[$key])) {
        $p->telefono = $fakes[$key];
        $p->save();
    }
}
echo "Teléfonos corregidos a números inválidos para evitar perfiles reales en WhatsApp.\n";
