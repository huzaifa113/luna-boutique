<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PosTerminal extends Component
{
    public string $search = '';

    public array $searchResults = [];

    public array $cart = [];

    public ?int $customerId = null;

    public string $walkInName = '';

    public string $walkInPhone = '';

    public float $discount = 0;

    public float $tax = 0;

    public float $deliveryCharges = 0;

    public string $paymentMethod = 'cash';

    public float $amountReceived = 0;

    public bool $showPaymentModal = false;

    public ?string $successMessage = null;

    public ?int $lastSaleId = null;

    public function updatedSearch(): void
    {
        $this->searchProducts();
    }

    public function searchProducts(): void
    {
        $query = trim($this->search);

        if (strlen($query) < 1) {
            $this->searchResults = [];

            return;
        }

        $products = Product::query()
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhereHas('productUnits', function ($q2) use ($query) {
                        $q2->where('barcode', 'like', "%{$query}%");
                    });
            })
            ->with(['baseUnit', 'productUnits.unit'])
            ->limit(10)
            ->get();

        $results = [];

        foreach ($products as $product) {
            $productUnits = $product->productUnits;

            // If the product has no configured product units, fall back to the base unit.
            if ($productUnits->isEmpty()) {
                $factor = 1;
                $saleRate = (float) $product->price;
                $stockAtUnit = (float) $product->stock_quantity;

                $results[] = [
                    'id' => $product->id,
                    'product_unit_id' => null,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $saleRate,
                    'stock' => $stockAtUnit,
                    'unit_name' => $product->baseUnit?->name ?? 'unit',
                    'factor' => $factor,
                    'track_stock' => (bool) $product->track_stock,
                ];

                continue;
            }

            // Show every unit level (bag, carton, pieces, kg, etc.) as its own result.
            foreach ($productUnits as $unit) {
                $factor = $unit->factor !== null && (float) $unit->factor > 0 ? (float) $unit->factor : 1;
                $saleRate = $unit->sale_rate ?? $product->price;
                $stockAtUnit = $factor > 0 ? round((float) $product->stock_quantity / $factor, 3) : 0;
                $unitName = $unit->unit?->name ?? $product->baseUnit?->name ?? 'unit';

                $results[] = [
                    'id' => $product->id,
                    'product_unit_id' => $unit->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => (float) $saleRate,
                    'stock' => $stockAtUnit,
                    'unit_name' => $unitName,
                    'factor' => $factor,
                    'track_stock' => (bool) $product->track_stock,
                ];
            }
        }

        $this->searchResults = $results;
    }

    public function addToCart(int $productId, ?int $productUnitId = null): void
    {
        $product = Product::with(['productUnits.unit'])->find($productId);
        if (! $product) {
            return;
        }

        if ($productUnitId) {
            $unit = $product->productUnits->firstWhere('id', $productUnitId);
        } else {
            $unit = $product->productUnits
                ->where('is_default_sale', true)
                ->first() ?? $product->productUnits->first();
        }

        $unitName = $unit?->unit?->name ?? $product->baseUnit?->name ?? 'unit';
        $factor = $unit?->factor !== null && (float) $unit->factor > 0 ? (float) $unit->factor : 1;
        $saleRate = $unit?->sale_rate ?? $product->price;
        $stockAtUnit = $factor > 0 ? round((float) $product->stock_quantity / $factor, 3) : 0;

        // Check if already in cart (distinct by product + unit)
        foreach ($this->cart as $index => $item) {
            if ($item['product_id'] === $productId && $item['product_unit_id'] === ($unit?->id ?: null)) {
                $newQty = $item['quantity'] + 1;
                if ($product->track_stock && $newQty > $stockAtUnit) {
                    $this->dispatch('pos-notify', message: 'Insufficient stock available.');

                    return;
                }
                $this->cart[$index]['quantity'] = $newQty;
                $this->cart[$index]['line_total'] = round($newQty * $item['price'], 2);
                $this->search = '';
                $this->searchResults = [];

                return;
            }
        }

        if ($product->track_stock && $stockAtUnit <= 0) {
            $this->dispatch('pos-notify', message: 'This product is out of stock.');

            return;
        }

        $this->cart[] = [
            'product_id' => $productId,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $saleRate,
            'quantity' => 1,
            'unit_name' => $unitName,
            'factor' => $factor,
            'product_unit_id' => $unit?->id,
            'track_stock' => (bool) $product->track_stock,
            'stock' => $stockAtUnit,
            'line_total' => round($saleRate, 2),
        ];

        $this->search = '';
        $this->searchResults = [];
    }

    public function updateQuantity(int $index, float $quantity): void
    {
        if (! isset($this->cart[$index])) {
            return;
        }

        if ($quantity <= 0) {
            $this->removeFromCart($index);

            return;
        }

        $item = $this->cart[$index];
        if ($item['track_stock'] && $quantity > $item['stock']) {
            $this->dispatch('pos-notify', message: 'Insufficient stock available.');

            return;
        }

        $this->cart[$index]['quantity'] = $quantity;
        $this->cart[$index]['line_total'] = round($quantity * $item['price'], 2);
    }

    public function removeFromCart(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->customerId = null;
        $this->walkInName = '';
        $this->walkInPhone = '';
        $this->discount = 0;
        $this->tax = 0;
        $this->deliveryCharges = 0;
        $this->amountReceived = 0;
    }

    public function getSubtotalProperty(): float
    {
        return round(array_sum(array_column($this->cart, 'line_total')), 2);
    }

    public function getTotalProperty(): float
    {
        return round($this->subtotal - $this->discount + $this->tax + $this->deliveryCharges, 2);
    }

    public function getChangeProperty(): float
    {
        return round(max(0, $this->amountReceived - $this->total), 2);
    }

    public function openPaymentModal(): void
    {
        if (empty($this->cart)) {
            $this->dispatch('pos-notify', message: 'Cart is empty. Add products first.');

            return;
        }

        $this->amountReceived = $this->total;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
    }

    public function completeSale(): void
    {
        if (empty($this->cart)) {
            return;
        }

        if ($this->amountReceived < $this->total) {
            $this->dispatch('pos-notify', message: 'Amount received is less than the total.');

            return;
        }

        DB::transaction(function () {
            $customerId = $this->customerId;

            // If a walk-in name was provided, create (or reuse) a customer record so
            // the sale shows up in the customer list, customer payments, and reports.
            if (! $customerId && trim($this->walkInName) !== '') {
                $customer = Customer::firstOrCreate(
                    ['name' => trim($this->walkInName)],
                    ['phone' => trim($this->walkInPhone) ?: null]
                );
                $customerId = $customer->id;
            }

            $sale = Sale::create([
                'customer_id' => $customerId,
                'walk_in_name' => $customerId ? null : ($this->walkInName ?: null),
                'walk_in_phone' => $customerId ? null : ($this->walkInPhone ?: null),
                'invoice_number' => $this->generateInvoiceNumber(),
                'sale_date' => now()->toDateString(),
                'status' => Sale::STATUS_COMPLETED,
                'subtotal' => $this->subtotal,
                'shortage_adjustment' => 0,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'delivery_charges' => $this->deliveryCharges,
                'total' => $this->total,
                'shortage_cost' => 0,
                'payment_status' => Sale::PAYMENT_STATUS_PAID,
                'notes' => "Payment method: {$this->paymentMethod}",
                'user_id' => auth()->id(),
            ]);

            foreach ($this->cart as $item) {
                $product = Product::find($item['product_id']);
                if (! $product) {
                    continue;
                }

                $baseQty = round($item['quantity'] * $item['factor'], 3);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'],
                    'unit_id' => $item['product_unit_id'] ? ProductUnit::find($item['product_unit_id'])?->unit_id : null,
                    'unit_name' => $item['unit_name'],
                    'factor' => $item['factor'],
                    'quantity' => $item['quantity'],
                    'gross_base_quantity' => $baseQty,
                    'shortage_quantity' => 0,
                    'billed_base_quantity' => $baseQty,
                    'rate' => $item['price'],
                    'base_unit_rate' => round($item['price'] / max($item['factor'], 1), 4),
                    'base_unit_cost' => round((float) $product->cost_price, 4),
                    'gross_amount' => $item['line_total'],
                    'shortage_amount' => 0,
                    'net_amount' => $item['line_total'],
                ]);

                // Update stock
                if ($product->track_stock) {
                    $newStock = round((float) $product->stock_quantity - $baseQty, 3);
                    $product->stock_quantity = max(0, $newStock);
                    $product->save();

                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => StockMovement::TYPE_OUT,
                        'reason' => StockMovement::REASON_SALE,
                        'base_quantity' => $baseQty,
                        'balance_after' => (float) $product->stock_quantity,
                        'unit_cost' => (float) $product->cost_price,
                        'reference_type' => Sale::class,
                        'reference_id' => $sale->id,
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            $this->successMessage = "Sale completed! Invoice #{$sale->invoice_number}";
            $this->lastSaleId = $sale->id;
            $this->showPaymentModal = false;
            $this->clearCart();
        });
    }

    protected function generateInvoiceNumber(): string
    {
        $prefix = config('pos.invoice.sale_prefix', 'INV-');
        $padding = config('pos.invoice.number_padding', 5);
        $last = Sale::where('invoice_number', 'like', "{$prefix}%")
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $lastNumber = $last ? (int) substr($last, strlen($prefix)) : 0;

        return $prefix . str_pad((string) ($lastNumber + 1), $padding, '0', STR_PAD_LEFT);
    }

    public function getCustomersProperty(): array
    {
        return Customer::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'phone'])
            ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'phone' => $c->phone])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.pos-terminal');
    }
}