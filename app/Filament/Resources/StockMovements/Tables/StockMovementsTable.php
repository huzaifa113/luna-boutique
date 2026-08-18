<?php

namespace App\Filament\Resources\StockMovements\Tables;

use App\Models\StockMovement;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Reason')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'purchase' => 'Purchase',
                        'purchase_cancel' => 'Purchase Cancel',
                        'sale' => 'Sale',
                        'sale_shortage' => 'Sale Shortage',
                        'sale_cancel' => 'Sale Cancel',
                        'online_order' => 'Online Order',
                        'online_return' => 'Online Return',
                        'adjustment' => 'Adjustment',
                        'opening' => 'Opening Stock',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('base_quantity')
                    ->label('Quantity')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),
                TextColumn::make('balance_after')
                    ->label('Balance After')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),
                TextColumn::make('unit_cost')
                    ->label('Unit Cost')
                    ->money(config('pos.currency.code', 'PKR'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}