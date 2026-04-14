<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

try {
    $user = User::firstOrCreate(
        ['email' => 'cliente@stitchco.com.ve'],
        [
            'nombre'   => 'María',
            'apellido' => 'González',
            'password' => Hash::make('cliente123'),
            'rol'      => 'cliente',
            'telefono' => '+58 414 1234567',
            'tipo_documento' => 'V',
            'documento_identidad' => '22000000',
        ]
    );

    $user->update([
        'password' => Hash::make('cliente123'),
        'rol'      => 'cliente'
    ]);

    echo "Client account checked/created successfully.\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->rol . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
