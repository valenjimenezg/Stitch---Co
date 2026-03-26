<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if (!$user) { 
    echo "No users found\n"; 
    exit; 
}

$venta = \App\Models\Venta::create([
    'user_id' => $user->id,
    'email' => $user->email,
    'nombre_cliente' => $user->nombre ?? 'Test MOCK',
    'telefono' => '123456',
    'direccion' => 'Test',
    'ciudad' => 'Test',
    'total_venta' => 100.50,
    'metodo_pago' => 'transferencia',
    'estado' => 'pending', // Starts precisely as mandated by Phase 3 logic
    'tipo_envio' => 'retiro_tienda',
    'costo_envio' => 0
]);

echo "Created Order ID: " . $venta->id . " Status: " . $venta->estado . "\n";

// Emulate ADMIN manually mutating Status
$controller = app(\App\Http\Controllers\Admin\OrderController::class);
$request = new \Illuminate\Http\Request(['estado' => 'paid']);
$controller->updateStatus($request, $venta->id);

$venta->refresh();
echo "Updated Order ID: " . $venta->id . " Status: " . $venta->estado . "\n";

if ($venta->invoice) {
    echo "SUCCESS: Invoice Generated! ID: " . $venta->invoice->id . " Amount: $" . $venta->invoice->monto . "\n";
} else {
    echo "FAILED: Invoice not instantiated.\n";
}

// Cleanup
if ($venta->invoice) $venta->invoice()->delete();
$venta->delete();
echo "Cleanup OK.\n";
