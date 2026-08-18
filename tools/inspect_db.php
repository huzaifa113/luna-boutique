<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
// Bootstrap the kernel so facades work
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;

try {
    $out = [];
    $out['products'] = DB::table('products')->count();
    $out['product_units'] = DB::table('product_units')->count();
    $out['units'] = DB::table('units')->count();
    $p = Product::with('productUnits')->where('is_active', 1)->first();
    if ($p) {
        $out['sample_product'] = ['id' => $p->id, 'name' => $p->name, 'product_units_count' => count($p->productUnits)];
        $out['sample_product_units'] = [];
        foreach ($p->productUnits as $u) {
            $out['sample_product_units'][] = ['unit_id' => $u->unit_id, 'sale_rate' => $u->sale_rate, 'is_default_sale' => $u->is_default_sale, 'is_base' => $u->is_base];
        }
    } else {
        $out['sample_product'] = null;
    }
    echo json_encode($out, JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    echo "ERROR: ";
    echo $e->getMessage() . "\n" . $e->getTraceAsString();
}
