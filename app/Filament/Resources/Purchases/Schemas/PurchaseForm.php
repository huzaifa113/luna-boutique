<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\VendorPayment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Vendor Information')
                    ->columnSpan(1)
                    ->columns(1)
                    ->components([
                        Select::make('vendor_id')
                            ->label('Vendor')
                            ->placeholder('Select a vendor')
                            ->relationship('vendor', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Contact Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('company')
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('city')
                                    ->maxLength(255),
                                TextInput::make('tax_number')
                                    ->label('NTN / Tax Number')
                                    ->maxLength(255),
                                Textarea::make('address')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                TextInput::make('opening_balance')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix(config('pos.currency.symbol', 'Rs')),
                                Toggle::make('is_active')
                                    ->default(true)
                                    ->inline(false),
                                Textarea::make('notes')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->createOptionUsing(fn (array $data) => static::createNewVendor($data)),
                        TextInput::make('vendor_invoice_no')
                            ->label('Vendor Invoice #'),
                        DatePicker::make('purchase_date')
                            ->label('Purchase Date')
                            ->default(now())
                            ->required(),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2),
                    ]),

                Section::make('Pricing')
                    ->columnSpan(1)
                    ->columns(2)
                    ->components([
                        TextInput::make('discount')
                            ->label('Discount')
                            ->numeric()
                            ->default(0)
                            ->step(0.01)
                            ->prefix(config('pos.currency.symbol', 'Rs'))
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set, callable $get) => static::updatePurchaseTotal($set, $get)),
                        TextInput::make('tax')
                            ->label('Tax')
                            ->numeric()
                            ->default(0)
                            ->step(0.01)
                            ->prefix(config('pos.currency.symbol', 'Rs'))
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set, callable $get) => static::updatePurchaseTotal($set, $get)),
                        TextInput::make('freight')
                            ->label('Freight')
                            ->numeric()
                            ->default(0)
                            ->step(0.01)
                            ->prefix(config('pos.currency.symbol', 'Rs'))
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set, callable $get) => static::updatePurchaseTotal($set, $get)),
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(true)
                            ->default(0),
                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(true)
                            ->default(0),
                    ]),

                Section::make('Payment')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Toggle::make('make_payment')
                            ->label('Make a payment for this purchase')
                            ->default(false)
                            ->reactive()
                            ->columnSpanFull(),
                        DatePicker::make('payment_date')
                            ->label('Payment Date')
                            ->default(now())
                            ->maxDate(now())
                            ->visible(fn (callable $get) => $get('make_payment')),
                        TextInput::make('payment_amount')
                            ->label('Amount')
                            ->numeric()
                            ->minValue(0.01)
                            ->prefix(config('pos.currency.symbol', 'Rs'))
                            ->required(fn (callable $get) => $get('make_payment'))
                            ->visible(fn (callable $get) => $get('make_payment')),
                        Select::make('payment_method')
                            ->label('Method')
                            ->options(array_combine(
                                VendorPayment::METHODS,
                                array_map(ucfirst(...), VendorPayment::METHODS),
                            ))
                            ->default(VendorPayment::METHOD_CASH)
                            ->visible(fn (callable $get) => $get('make_payment')),
                        TextInput::make('payment_reference_no')
                            ->label('Reference / Cheque No.')
                            ->maxLength(255)
                            ->visible(fn (callable $get) => $get('make_payment')),
                        Textarea::make('payment_notes')
                            ->label('Notes')
                            ->rows(2)
                            ->columnSpanFull()
                            ->visible(fn (callable $get) => $get('make_payment')),
                    ]),

                Section::make('Purchase Items')
                    ->columnSpanFull()
                    ->components([
                        Repeater::make('items')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->options(fn () => Product::where('is_active', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->createOptionForm([
                                        Select::make('base_unit_id')
                                            ->label('Base Unit')
                                            ->options(fn () => Unit::where('is_active', true)->pluck('name', 'id'))
                                            ->searchable()
                                            ->required(),
                                        TextInput::make('name')
                                            ->label('Product Name')
                                            ->required(),
                                        TextInput::make('sku')
                                            ->label('SKU')
                                            ->required()
                                            ->unique('products', 'sku'),
                                        TextInput::make('price')
                                            ->label('Selling Price')
                                            ->numeric()
                                            ->default(0)
                                            ->prefix(config('pos.currency.symbol', 'Rs')),
                                        TextInput::make('cost_price')
                                            ->label('Cost Price')
                                            ->numeric()
                                            ->default(0)
                                            ->prefix(config('pos.currency.symbol', 'Rs')),
                                    ])
                                    ->createOptionUsing(fn (array $data) => static::createNewProduct($data))
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('product_name', $product->name);
                                                $set('product_sku', $product->sku);
                                                $defaultUnit = $product->productUnits()->where('is_default_purchase', true)->first();
                                                if ($defaultUnit) {
                                                    $set('unit_id', $defaultUnit->unit_id);
                                                    $set('unit_name', $defaultUnit->unit->name);
                                                    $set('factor', $defaultUnit->factor);
                                                }
                                            }
                                        }
                                        static::updateItemTotals($set, $get);
                                    }),
                                TextInput::make('product_name')
                                    ->hidden()
                                    ->dehydrated(true),
                                TextInput::make('product_sku')
                                    ->hidden()
                                    ->dehydrated(true),
                                Select::make('unit_id')
                                    ->label('Unit')
                                    ->options(fn () => Unit::where('is_active', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $productId = $get('product_id');
                                        if ($productId && $state) {
                                            $productUnit = ProductUnit::where('product_id', $productId)
                                                ->where('unit_id', $state)
                                                ->first();
                                            if ($productUnit) {
                                                $set('factor', $productUnit->factor);
                                            } else {
                                                $set('factor', 1);
                                            }
                                            $unit = Unit::find($state);
                                            $set('unit_name', $unit ? $unit->name : '');
                                        }
                                        static::updateItemTotals($set, $get);
                                    }),
                                TextInput::make('unit_name')
                                    ->hidden()
                                    ->dehydrated(true),
                                TextInput::make('factor')
                                    ->label(fn (callable $get) => static::getFactorLabel($get('unit_id')))
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->step(0.001)
                                    ->visible(fn (callable $get) => static::isBagOrCarton($get('unit_id')))
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                        static::updateItemTotals($set, $get);
                                    }),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->step(0.001)
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                        static::updateItemTotals($set, $get);
                                    }),
                                TextInput::make('rate')
                                    ->label('Rate')
                                    ->numeric()
                                    ->required()
                                    ->step(0.01)
                                    ->prefix(config('pos.currency.symbol', 'Rs'))
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                        static::updateItemTotals($set, $get);
                                    }),
                                TextInput::make('gross_amount')
                                    ->label('Total')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->default(0)
                                    ->prefix(config('pos.currency.symbol', 'Rs')),
                                TextInput::make('gross_base_quantity')
                                    ->hidden()
                                    ->dehydrated(true)
                                    ->default(0),
                                TextInput::make('base_unit_rate')
                                    ->hidden()
                                    ->dehydrated(true)
                                    ->default(0),
                                TextInput::make('shortage_quantity')
                                    ->label('Shortage (kg/pcs)')
                                    ->numeric()
                                    ->default(0)
                                    ->step(0.001)
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                        static::updateItemTotals($set, $get);
                                    }),
                                TextInput::make('shortage_amount')
                                    ->label('Shortage Amt')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->default(0)
                                    ->prefix(config('pos.currency.symbol', 'Rs')),
                                TextInput::make('net_amount')
                                    ->label('Net Amount')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->default(0)
                                    ->prefix(config('pos.currency.symbol', 'Rs')),
                                TextInput::make('received_base_quantity')
                                    ->hidden()
                                    ->dehydrated(true)
                                    ->default(0),
                                Textarea::make('notes')
                                    ->label('Notes')
                                    ->columnSpanFull(),
                            ])
                            ->columns(4)
                            ->defaultItems(0)
                            ->addActionLabel('Add Item'),
                    ]),
            ]);
    }

    /**
     * Create a new product on-the-fly from the purchase form's "＋ Create" option.
     */
    protected static function createNewProduct(array $data): int
    {
        $category = Category::first();

        $product = Product::create([
            'category_id' => $category?->id ?? 1,
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(6)),
            'sku' => $data['sku'],
            'price' => $data['price'] ?? 0,
            'cost_price' => $data['cost_price'] ?? 0,
            'stock_quantity' => 0,
            'base_unit_id' => $data['base_unit_id'],
            'track_stock' => true,
            'is_active' => true,
            'is_featured' => false,
            'adv_payment' => false,
        ]);

        return $product->id;
    }

    /**
     * Create a new vendor on-the-fly from the purchase form's "＋ Create" option.
     */
    protected static function createNewVendor(array $data): int
    {
        $vendor = Vendor::create([
            'name' => $data['name'],
            'company' => $data['company'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'city' => $data['city'] ?? null,
            'tax_number' => $data['tax_number'] ?? null,
            'address' => $data['address'] ?? null,
            'opening_balance' => $data['opening_balance'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
            'notes' => $data['notes'] ?? null,
        ]);

        return $vendor->id;
    }

    protected static function getFactorLabel(mixed $unitId): string
    {
        if (! $unitId) {
            return 'Factor';
        }

        $unit = Unit::find($unitId);

        if (! $unit) {
            return 'Factor';
        }

        return match (strtolower($unit->name)) {
            'bag' => 'Kg per Bag',
            'carton' => 'Pieces in Carton',
            default => 'Factor',
        };
    }

    protected static function isBagOrCarton(mixed $unitId): bool
    {
        if (! $unitId) {
            return false;
        }

        $unit = Unit::find($unitId);

        if (! $unit) {
            return false;
        }

        return in_array(strtolower($unit->name), ['bag', 'carton']);
    }

    protected static function updateItemTotals(callable $set, callable $get): void
    {
        $quantity = (float) ($get('quantity') ?: 0);
        $rate = (float) ($get('rate') ?: 0);
        $factor = (float) ($get('factor') ?: 1);
        $shortageQty = (float) ($get('shortage_quantity') ?: 0);

        $grossAmount = round($quantity * $rate, 2);
        $grossBaseQty = round($quantity * $factor, 3);
        $baseUnitRate = $factor > 0 ? round($rate / $factor, 4) : 0;
        $shortageAmount = round($shortageQty * $baseUnitRate, 2);
        $netAmount = round($grossAmount - $shortageAmount, 2);
        $receivedBaseQty = round($grossBaseQty - $shortageQty, 3);

        $set('gross_amount', $grossAmount);
        $set('gross_base_quantity', $grossBaseQty);
        $set('base_unit_rate', $baseUnitRate);
        $set('shortage_amount', $shortageAmount);
        $set('net_amount', $netAmount);
        $set('received_base_quantity', $receivedBaseQty);
    }

    protected static function updatePurchaseTotal(callable $set, callable $get): void
    {
        $subtotal = (float) ($get('subtotal') ?: 0);
        $discount = (float) ($get('discount') ?: 0);
        $tax = (float) ($get('tax') ?: 0);
        $freight = (float) ($get('freight') ?: 0);

        $total = round($subtotal - $discount + $tax + $freight, 2);
        $set('total', $total);
    }
}