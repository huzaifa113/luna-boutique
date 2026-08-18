<?php

namespace App\Filament\Resources\Sales\Tables;

use App\Models\Sale;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (?string $state, Sale $record): string => $state ?: ($record->walk_in_name ?: 'Walk-in')),
                TextColumn::make('sale_date')
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
                TextColumn::make('total')
                    ->label('Total')
                    ->money(config('pos.currency.code', 'PKR'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'draft' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'unpaid' => 'danger',
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
                Action::make('print_receipt')
                    ->label('Print Receipt')
                    ->icon(Heroicon::OutlinedReceiptPercent)
                    ->color('info')
                    ->url(fn (Sale $record): string => route('pos.sales.receipt', $record))
                    ->openUrlInNewTab(),
                Action::make('print_a4')
                    ->label('Print A4 Invoice')
                    ->icon(Heroicon::OutlinedPrinter)
                    ->color('gray')
                    ->url(fn (Sale $record): string => route('pos.sales.invoice', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}