<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES'); 
foreach($tables as $t) { 
    $table = array_values((array)$t)[0]; 
    try { 
        $res = DB::table($table)->get(); 
        foreach($res as $r) { 
            foreach($r as $k => $v) { 
                if(is_string($v) && stripos($v, 'delcy') !== false) { 
                    echo "Found in table: $table, column: $k, id: {$r->id} \n"; 
                } 
                if(is_string($v) && stripos($v, 'rodriguez') !== false) { 
                    echo "Found in table: $table, column: $k, id: {$r->id} \n"; 
                }
            } 
        } 
    } catch (\Exception $e) {} 
}
