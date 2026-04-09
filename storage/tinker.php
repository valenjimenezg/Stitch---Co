<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/api/search', 'GET', ['q' => 'tela']);
$response = app()->handle($request);
file_put_contents(__DIR__.'/test_output.json', $response->getContent());
echo "Saved to test_output.json";
