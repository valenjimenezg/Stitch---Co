<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

try {
    $admin = User::where('email', 'admin@stitchco.com.ve')->first();
    if($admin) {
        $admin->update(['password' => Hash::make('stitchAdmin99')]);
        echo "Admin password updated.\n";
    }

    $cliente = User::where('email', 'cliente@stitchco.com.ve')->first();
    if($cliente) {
        $cliente->update(['password' => Hash::make('mariaTienda12')]);
        echo "Client password updated.\n";
    }

    echo "Done.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
