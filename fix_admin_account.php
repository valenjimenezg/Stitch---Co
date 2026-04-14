<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

try {
    $user = User::firstOrCreate(
        ['email' => 'admin@stitchco.com.ve'],
        [
            'nombre'   => 'Admin',
            'apellido' => 'Stitch',
            'password' => Hash::make('admin123'),
            'rol'      => 'admin',
            'telefono' => '+58 412 0000000',
            'tipo_documento' => 'V',
            'documento_identidad' => '11000000',
        ]
    );

    $user->update([
        'password' => Hash::make('admin123'),
        'rol'      => 'admin'
    ]);

    echo "Admin account checked/created successfully.\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->rol . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
