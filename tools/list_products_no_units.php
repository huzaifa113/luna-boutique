<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
try {
    $rows = DB::select('SELECT p.id, p.name FROM products p LEFT JOIN product_units pu ON p.id = pu.product_id WHERE pu.product_id IS NULL LIMIT 50');
    echo json_encode($rows, JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    echo 'ERROR: '.$e->getMessage();
}
