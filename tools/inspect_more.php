<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $out = [];
    $tables = ['purchases','purchase_items','sales','sale_items','stock_movements','customers','orders','order_items'];
    foreach ($tables as $t) {
        $out[$t] = DB::table($t)->count();
    }
    echo json_encode($out, JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    echo "ERROR: ". $e->getMessage();
}
