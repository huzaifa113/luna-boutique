<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductUnit;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\VendorPayment;
use Illuminate\Database\Seeder;

class PosDemoSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------------
        // 1. Categories
        // ---------------------------------------------------------------
        $categories = collect([
            ['name' => 'Groceries', 'slug' => 'groceries'],
            ['name' => 'Beverages', 'slug' => 'beverages'],
            ['name' => 'Snacks', 'slug' => 'snacks'],
            ['name' => 'Cooking Essentials', 'slug' => 'cooking-essentials'],
            ['name' => 'Dairy & Chilled', 'slug' => 'dairy-chilled'],
        ]);

        $categoryIds = $categories->map(fn ($cat) => Category::firstOrCreate(
            ['slug' => $cat['slug']],
            ['name' => $cat['name'], 'is_active' => true]
        )->id);

        // ---------------------------------------------------------------
        // 2. Brands
        // ---------------------------------------------------------------
        $brands = collect(['National', 'Shan', 'Tapal', 'Lipton', 'PIA', 'Khan', 'Habib', 'Nestle', 'Engro', 'Mitchells']);
        $brandIds = $brands->map(fn ($name) => Brand::firstOrCreate(
            ['slug' => str()->slug($name)],
            ['name' => $name, 'is_active' => true]
        )->id);

        // ---------------------------------------------------------------
        // 3. Units (already seeded via UnitSeeder, but ensure they exist)
        // ---------------------------------------------------------------
        $pcs = Unit::firstOrCreate(['code' => 'pcs'], ['name' => 'Piece', 'type' => Unit::TYPE_COUNT, 'is_active' => true]);
        $kg = Unit::firstOrCreate(['code' => 'kg'], ['name' => 'Kilogram', 'type' => Unit::TYPE_WEIGHT, 'is_active' => true]);
        $g = Unit::firstOrCreate(['code' => 'g'], ['name' => 'Gram', 'type' => Unit::TYPE_WEIGHT, 'is_active' => true]);
        $bag = Unit::firstOrCreate(['code' => 'bag'], ['name' => 'Bag', 'type' => Unit::TYPE_COUNT, 'is_active' => true]);
        $carton = Unit::firstOrCreate(['code' => 'carton'], ['name' => 'Carton', 'type' => Unit::TYPE_COUNT, 'is_active' => true]);
        $dozen = Unit::firstOrCreate(['code' => 'dozen'], ['name' => 'Dozen', 'type' => Unit::TYPE_COUNT, 'is_active' => true]);
        $litre = Unit::firstOrCreate(['code' => 'litre'], ['name' => 'Litre', 'type' => Unit::TYPE_VOLUME, 'is_active' => true]);

        // ---------------------------------------------------------------
        // 4. Create 3 vendors
        // ---------------------------------------------------------------
        $vendorData = [
            ['name' => 'Wholesale Traders', 'company' => 'Wholesale Traders Co.', 'city' => 'Karachi', 'phone' => '021-111222333'],
            ['name' => 'Prime Supplies', 'company' => 'Prime Supplies Ltd.', 'city' => 'Lahore', 'phone' => '042-444555666'],
            ['name' => 'City Distributors', 'company' => 'City Distributors Inc.', 'city' => 'Islamabad', 'phone' => '051-777888999'],
        ];

        $vendors = collect();
        foreach ($vendorData as $vd) {
            $vendors->push(Vendor::factory()->create($vd));
        }

        // ---------------------------------------------------------------
        // 5. Create 5 customers
        // ---------------------------------------------------------------
        $customerData = [
            ['name' => 'Ahmed Ali', 'phone' => '0300-1234567', 'city' => 'Karachi'],
            ['name' => 'Sara Khan', 'phone' => '0301-2345678', 'city' => 'Lahore'],
            ['name' => 'Bilal Hussain', 'phone' => '0302-3456789', 'city' => 'Islamabad'],
            ['name' => 'Fatima Zaidi', 'phone' => '0303-4567890', 'city' => 'Karachi'],
            ['name' => 'Omar Farooq', 'phone' => '0304-5678901', 'city' => 'Lahore'],
        ];

        $customers = collect();
        foreach ($customerData as $cd) {
            $customers->push(Customer::factory()->create($cd));
        }

        // ---------------------------------------------------------------
        // 6. Create demo products
        // ---------------------------------------------------------------
        $productDefinitions = [
            // [name, categoryIndex, brandIndex, stockQty, costPrice, baseUnit, extraUnits]
            ['Premium Basmati Rice', 0, 0, 350, 180, $kg, [
                [$bag, 20, 3600], // Bag factor 20, price 3600
            ]],
            ['Organic Wheat Flour', 0, 1, 200, 65, $kg, [
                [$bag, 10, 1300],
            ]],
            ['Cooking Oil 5L', 3, 2, 80, 320, $pcs, [
                [$carton, 4, 1280],
            ]],
            ['Refined Sugar 1kg', 0, 3, 500, 95, $pcs, [
                [$bag, 25, 2375],
                [$carton, 50, 4750],
            ]],
            ['Premium Tea 200g', 1, 4, 150, 280, $pcs, [
                [$carton, 24, 6720],
            ]],
            ['Milk Powder 500g', 4, 5, 90, 450, $pcs, [
                [$carton, 12, 5400],
            ]],
            ['Canned Beans 400g', 0, 6, 300, 85, $pcs, [
                [$carton, 24, 2040],
            ]],
            ['Pasta Penne 500g', 0, 7, 180, 75, $pcs, [
                [$carton, 20, 1500],
            ]],
            ['Tomato Ketchup 1kg', 3, 8, 120, 145, $pcs, [
                [$carton, 12, 1740],
            ]],
            ['Mayonnaise Jar 500g', 3, 9, 65, 220, $pcs, [
                [$carton, 12, 2640],
            ]],
            ['Chicken Spice Mix 50g', 3, 1, 400, 35, $pcs, [
                [$carton, 100, 3500],
            ]],
            ['Iodized Salt 1kg', 0, 0, 600, 20, $pcs, [
                [$bag, 25, 500],
            ]],
            ['Mineral Water 1.5L', 1, 3, 240, 30, $pcs, [
                [$carton, 6, 180],
            ]],
            ['Orange Juice 1L', 1, 7, 100, 120, $pcs, [
                [$carton, 12, 1440],
            ]],
            ['Biscuits Pack 200g', 2, 8, 350, 40, $pcs, [
                [$carton, 24, 960],
            ]],
            ['Corn Flakes 500g', 2, 5, 70, 220, $pcs, [
                [$carton, 12, 2640],
            ]],
            ['Chocolate Bar 100g', 2, 6, 250, 80, $pcs, [
                [$carton, 24, 1920],
            ]],
            ['Instant Noodles Pack', 0, 4, 400, 15, $pcs, [
                [$carton, 40, 600],
            ]],
            ['Cooking Sauce 500g', 3, 9, 85, 160, $pcs, [
                [$carton, 12, 1920],
            ]],
            ['Olive Oil 500ml', 3, 2, 45, 480, $pcs, [
                [$carton, 6, 2880],
            ]],
        ];

        $products = collect();
        $prodIdx = 0;
        foreach ($productDefinitions as $def) {
            $catId = $categoryIds[$def[1]] ?? Category::first()->id;
            $brandId = $brandIds[$def[2]] ?? Brand::first()->id;

            $product = Product::factory()->create([
                'name' => $def[0],
                'slug' => str()->slug($def[0]),
                'sku' => 'SKU-' . strtoupper(str()->random(6)),
                'category_id' => $catId,
                'brand_id' => $brandId,
                'stock_quantity' => $def[3],
                'cost_price' => $def[4],
                'price' => $def[4] * 1.3, // 30% markup
                'short_description' => 'High quality ' . $def[0],
            ]);

            // Add a product image
            ProductImage::factory()->create([
                'product_id' => $product->id,
                'path' => 'https://picsum.photos/seed/prod' . $prodIdx . '/400/400',
            ]);

            // Base unit (default sale)
            $baseUnit = $def[5];
            ProductUnit::create([
                'product_id' => $product->id,
                'unit_id' => $baseUnit->id,
                'factor' => 1,
                'is_base' => true,
                'is_default_sale' => true,
                'sale_rate' => round($def[4] * 1.3, 2),
                'purchase_rate' => $def[4],
            ]);

            // Extra units (bag, carton, etc.)
            foreach ($def[6] as $extra) {
                [$extraUnit, $factor, $saleRate] = $extra;
                ProductUnit::create([
                    'product_id' => $product->id,
                    'unit_id' => $extraUnit->id,
                    'factor' => $factor,
                    'is_base' => false,
                    'is_default_purchase' => $extraUnit->id === $bag->id || $extraUnit->id === $carton->id,
                    'sale_rate' => $saleRate,
                    'purchase_rate' => round($saleRate / 1.3, 2),
                ]);
            }

            $products->push($product);
            $prodIdx++;
        }

        // ---------------------------------------------------------------
        // 7. Create purchases (confirmed) with items
        // ---------------------------------------------------------------
        $purchaseItems = [
            // [vendorIndex, purchaseDate, items: [productIndex, qty, rate, shortage]]
            [0, now()->subDays(15), [
                [0, 10, 180, 0.5],
                [1, 8, 65, 0],
                [11, 20, 20, 0],
                [12, 15, 30, 0],
            ]],
            [1, now()->subDays(12), [
                [2, 5, 320, 0],
                [4, 12, 280, 1],
                [8, 10, 145, 0],
                [9, 5, 220, 0],
            ]],
            [2, now()->subDays(8), [
                [3, 25, 95, 0],
                [7, 10, 75, 0],
                [14, 20, 40, 0],
                [17, 30, 15, 0],
            ]],
            [0, now()->subDays(5), [
                [5, 4, 450, 0],
                [6, 15, 85, 0.2],
                [10, 30, 35, 0],
                [15, 5, 220, 0],
            ]],
            [1, now()->subDays(3), [
                [13, 8, 120, 0],
                [16, 15, 80, 0],
                [18, 6, 160, 0],
                [19, 3, 480, 0],
            ]],
        ];

        foreach ($purchaseItems as $pi) {
            $vendor = $vendors[$pi[0]];
            $purchaseDate = $pi[1];

            $purchase = Purchase::factory()->confirmed()->create([
                'vendor_id' => $vendor->id,
                'purchase_date' => $purchaseDate,
                'user_id' => auth()->id() ?? 1,
            ]);

            foreach ($pi[2] as $itemDef) {
                $product = $products[$itemDef[0]];
                $qty = $itemDef[1];
                $rate = $itemDef[2];
                $shortageQty = $itemDef[3];

                $purchaseUnit = $product->productUnits()->where('is_default_purchase', true)->first()
                    ?? $product->productUnits()->where('is_base', true)->first();

                if (! $purchaseUnit) {
                    continue;
                }

                $factor = (float) $purchaseUnit->factor;
                $grossBaseQty = round($qty * $factor, 3);
                $grossAmount = round($qty * $rate, 2);
                $baseUnitRate = $grossBaseQty > 0 ? round($grossAmount / $grossBaseQty, 4) : 0;
                $receivedBaseQty = $grossBaseQty - $shortageQty;
                $shortageAmount = round($shortageQty * $baseUnitRate, 2);
                $netAmount = $grossAmount - $shortageAmount;

                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'unit_id' => $purchaseUnit->unit_id,
                    'unit_name' => $purchaseUnit->unit->name,
                    'factor' => $factor,
                    'quantity' => $qty,
                    'gross_base_quantity' => $grossBaseQty,
                    'shortage_quantity' => $shortageQty,
                    'received_base_quantity' => $receivedBaseQty,
                    'rate' => $rate,
                    'base_unit_rate' => $baseUnitRate,
                    'gross_amount' => $grossAmount,
                    'shortage_amount' => $shortageAmount,
                    'net_amount' => $netAmount,
                ]);

                // Update stock for confirmed purchase
                $product->increment('stock_quantity', $receivedBaseQty);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => StockMovement::TYPE_IN,
                    'reason' => StockMovement::REASON_PURCHASE,
                    'base_quantity' => $receivedBaseQty,
                    'balance_after' => (float) $product->stock_quantity,
                    'unit_cost' => $baseUnitRate,
                    'reference_type' => Purchase::class,
                    'reference_id' => $purchase->id,
                    'user_id' => auth()->id() ?? 1,
                ]);

                // Update cost price weighted average
                $currentStock = (float) $product->stock_quantity;
                $qtyBefore = $currentStock - $receivedBaseQty;
                $currentCost = (float) $product->cost_price;
                $totalValue = ($qtyBefore * $currentCost) + ($receivedBaseQty * $baseUnitRate);
                if ($currentStock > 0) {
                    $product->update(['cost_price' => round($totalValue / $currentStock, 2)]);
                }
            }
        }

        // ---------------------------------------------------------------
        // 8. Create sales (completed) with items
        // ---------------------------------------------------------------
        $saleItems = [
            // [customerIndex, saleDate, discount, items: [productIndex, qty, rate, shortage]]
            [0, now()->subDays(4), 50, [
                [0, 2, 240, 0],
                [4, 1, 380, 0],
                [11, 5, 30, 0],
            ]],
            [1, now()->subDays(3), 0, [
                [2, 1, 420, 0],
                [8, 2, 190, 0],
                [13, 3, 160, 0],
            ]],
            [2, now()->subDays(2), 100, [
                [3, 5, 130, 0],
                [6, 3, 110, 0],
                [14, 10, 55, 0],
                [17, 12, 25, 0],
            ]],
            [3, now()->subDays(1), 30, [
                [1, 3, 85, 0],
                [5, 1, 580, 0],
                [9, 2, 290, 0],
                [16, 5, 105, 0],
            ]],
            [4, now()->subDays(0), 0, [
                [7, 4, 100, 0],
                [10, 8, 48, 0],
                [12, 6, 42, 0],
                [15, 2, 290, 0],
                [18, 1, 210, 0],
            ]],
        ];

        foreach ($saleItems as $si) {
            $customer = $customers[$si[0]];
            $saleDate = $si[1];
            $discount = $si[2];

            $sale = Sale::factory()->completed()->create([
                'customer_id' => $customer->id,
                'sale_date' => $saleDate,
                'discount' => $discount,
                'user_id' => auth()->id() ?? 1,
            ]);

            $totalAmount = 0;

            foreach ($si[3] as $itemDef) {
                $product = $products[$itemDef[0]];
                $qty = $itemDef[1];
                $rate = $itemDef[2];
                $shortageQty = $itemDef[3];

                $saleUnit = $product->productUnits()->where('is_default_sale', true)->first()
                    ?? $product->productUnits()->where('is_base', true)->first();

                if (! $saleUnit) {
                    continue;
                }

                $factor = (float) $saleUnit->factor;
                $grossBaseQty = round($qty * $factor, 3);
                $grossAmount = round($qty * $rate, 2);
                $baseUnitRate = $grossBaseQty > 0 ? round($grossAmount / $grossBaseQty, 4) : 0;
                $billedBaseQty = $grossBaseQty - $shortageQty;
                $shortageAmount = round($shortageQty * $baseUnitRate, 2);
                $netAmount = $grossAmount - $shortageAmount;
                $baseUnitCost = (float) $product->cost_price;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'unit_id' => $saleUnit->unit_id,
                    'unit_name' => $saleUnit->unit->name,
                    'factor' => $factor,
                    'quantity' => $qty,
                    'gross_base_quantity' => $grossBaseQty,
                    'shortage_quantity' => $shortageQty,
                    'billed_base_quantity' => $billedBaseQty,
                    'rate' => $rate,
                    'base_unit_rate' => $baseUnitRate,
                    'base_unit_cost' => $baseUnitCost,
                    'gross_amount' => $grossAmount,
                    'shortage_amount' => $shortageAmount,
                    'net_amount' => $netAmount,
                ]);

                $totalAmount += $netAmount;

                // Decrease stock
                $product->decrement('stock_quantity', $billedBaseQty);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => StockMovement::TYPE_OUT,
                    'reason' => StockMovement::REASON_SALE,
                    'base_quantity' => $billedBaseQty,
                    'balance_after' => (float) $product->stock_quantity,
                    'unit_cost' => $baseUnitCost,
                    'reference_type' => Sale::class,
                    'reference_id' => $sale->id,
                    'user_id' => auth()->id() ?? 1,
                ]);
            }

            $subtotal = round($totalAmount, 2);
            $total = round($subtotal - $discount, 2);

            $sale->update([
                'subtotal' => $subtotal,
                'total' => $total,
                'status' => Sale::STATUS_COMPLETED,
            ]);
        }

        // ---------------------------------------------------------------
        // 9. Add some payments
        // ---------------------------------------------------------------
        // Vendor payments
        VendorPayment::factory()->create([
            'vendor_id' => $vendors[0]->id,
            'amount' => 5000,
            'payment_date' => now()->subDays(10),
            'method' => 'cash',
            'user_id' => auth()->id() ?? 1,
        ]);

        VendorPayment::factory()->create([
            'vendor_id' => $vendors[1]->id,
            'amount' => 8000,
            'payment_date' => now()->subDays(7),
            'method' => 'bank',
            'user_id' => auth()->id() ?? 1,
        ]);

        // Customer payments
        CustomerPayment::factory()->create([
            'customer_id' => $customers[0]->id,
            'amount' => 1200,
            'payment_date' => now()->subDays(2),
            'method' => 'cash',
            'user_id' => auth()->id() ?? 1,
        ]);

        CustomerPayment::factory()->create([
            'customer_id' => $customers[2]->id,
            'amount' => 2000,
            'payment_date' => now()->subDays(1),
            'method' => 'jazzcash',
            'user_id' => auth()->id() ?? 1,
        ]);
    }
}