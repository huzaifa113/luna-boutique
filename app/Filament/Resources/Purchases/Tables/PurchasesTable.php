<?php

namespace App\Filament\Resources\Purchases\Tables;

use App\Models\Purchase;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vendor_invoice_no')
                    ->label('Vendor Invoice #')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('purchase_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money(config('pos.currency.code', 'PKR'))
                    ->sortable(),
                TextColumn::make('discount')
                    ->label('Discount')
                    ->money(config('pos.currency.code', 'PKR'))
                    ->sortable(),
                TextColumn::make('tax')
                    ->label('Tax')
                    ->money(config('pos.currency.code', 'PKR'))
                    ->sortable(),
                TextColumn::make('freight')
                    ->label('Freight')
                    ->money(config('pos.currency.code', 'PKR'))
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money(config('pos.currency.code', 'PKR'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'draft' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('print_a4')
                    ->label('Print A4 Invoice')
                    ->icon(Heroicon::OutlinedPrinter)
                    ->color('info')
                    ->url(fn (Purchase $record): string => route('pos.purchases.invoice', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}