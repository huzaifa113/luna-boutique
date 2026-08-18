<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Models\Customer;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Customer Information')
                    ->columnSpan(1)
                    ->columns(1)
                    ->components([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->placeholder('Select a customer')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload(),
                        DatePicker::make('sale_date')
                            ->label('Sale Date')
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
                            ->prefix(config('pos.currency.symbol', 'Rs')),
                        TextInput::make('tax')
                            ->label('Tax')
                            ->numeric()
                            ->default(0)
                            ->step(0.01)
                            ->prefix(config('pos.currency.symbol', 'Rs')),
                        TextInput::make('delivery_charges')
                            ->label('Delivery Charges')
                            ->numeric()
                            ->default(0)
                            ->step(0.01)
                            ->prefix(config('pos.currency.symbol', 'Rs')),
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(true),
                    ]),

                Section::make('Sale Items')
                    ->columnSpanFull()
                    ->components([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->options(fn () => Product::where('is_active', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('product_name', $product->name);
                                                $set('product_sku', $product->sku);
                                            }
                                        }
                                    }),
                                TextInput::make('product_name')
                                    ->label('Product Name')
                                    ->hidden()
                                    ->dehydrated(false),
                                TextInput::make('product_sku')
                                    ->label('SKU')
                                    ->hidden()
                                    ->dehydrated(false),
                                Select::make('unit_id')
                                    ->label('Unit')
                                    ->options(fn () => \App\Models\Unit::where('is_active', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->step(0.001),
                                TextInput::make('rate')
                                    ->label('Rate')
                                    ->numeric()
                                    ->required()
                                    ->step(0.01)
                                    ->prefix(config('pos.currency.symbol', 'Rs')),
                                TextInput::make('shortage_quantity')
                                    ->label('Shortage')
                                    ->numeric()
                                    ->default(0)
                                    ->step(0.001),
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
}