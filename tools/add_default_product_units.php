<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Unit;

try {
    $defaultUnit = DB::table('units')->orderBy('id')->first();
    if (! $defaultUnit) {
        echo "No units defined in 'units' table. Aborting.\n";
        exit(1);
    }
    $unitId = $defaultUnit->id;

    $rows = DB::select('SELECT p.id, p.price FROM products p LEFT JOIN product_units pu ON p.id = pu.product_id WHERE pu.product_id IS NULL');
    $inserted = 0;
    DB::beginTransaction();
    foreach ($rows as $r) {
        $price = $r->price ?? 0;
        DB::table('product_units')->insert([
            'product_id' => $r->id,
            'unit_id' => $unitId,
            'factor' => 1,
            'is_base' => true,
            'is_default_purchase' => false,
            'is_default_sale' => true,
            'purchase_rate' => null,
            'sale_rate' => $price,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $inserted++;
    }
    DB::commit();
    echo "Inserted {$inserted} default product_units using unit_id={$unitId}.\n";
} catch (Throwable $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
