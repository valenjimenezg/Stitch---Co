<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\Mail::raw('Prueba', function($msg) {
        $msg->to('valengomezb@gmail.com')->subject('Test');
    });
    echo 'OK';
} catch(\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
