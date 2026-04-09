<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo \Illuminate\Support\Facades\Artisan::output();
} catch (\Throwable $e) {
    echo "ERROR MESSAGE: " . $e->getMessage() . "\n";
    if (method_exists($e, 'getSql')) echo "SQL: " . $e->getSql() . "\n";
}
