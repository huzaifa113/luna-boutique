<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use App\Models\Product;
use App\Models\StockMovement;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Movement Details')
                    ->columnSpanFull()
                    ->columns(3)
                    ->components([
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
                                        $set('current_stock', (float) $product->stock_quantity);
                                    }
                                }
                            }),
                        TextInput::make('current_stock')
                            ->label('Current Stock')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->default(0),
                        Select::make('type')
                            ->label('Movement Type')
                            ->options([
                                StockMovement::TYPE_IN => 'Stock In',
                                StockMovement::TYPE_OUT => 'Stock Out',
                            ])
                            ->required()
                            ->reactive(),
                        Select::make('reason')
                            ->label('Reason')
                            ->options([
                                StockMovement::REASON_ADJUSTMENT => 'Adjustment',
                                StockMovement::REASON_OPENING => 'Opening Stock',
                                StockMovement::REASON_PURCHASE => 'Purchase',
                                StockMovement::REASON_PURCHASE_CANCEL => 'Purchase Cancel',
                                StockMovement::REASON_SALE => 'Sale',
                                StockMovement::REASON_SALE_CANCEL => 'Sale Cancel',
                                StockMovement::REASON_ONLINE_ORDER => 'Online Order',
                                StockMovement::REASON_ONLINE_RETURN => 'Online Return',
                            ])
                            ->required()
                            ->searchable(),
                        TextInput::make('base_quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->step(0.001)
                            ->rule('gt:0'),
                        TextInput::make('unit_cost')
                            ->label('Unit Cost')
                            ->numeric()
                            ->step(0.01)
                            ->prefix(config('pos.currency.symbol', 'Rs'))
                            ->helperText('Optional — only for stock-in movements'),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->columnSpanFull()
                            ->rows(2),
                    ]),
            ]);
    }
}