<?php

namespace App\Filament\Resources\ReturnExchanges\Tables;

use App\Models\ReturnExchange;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReturnExchangesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order.order_number')
                    ->label('Order')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        ReturnExchange::STATUS_PENDING => 'warning',
                        ReturnExchange::STATUS_ITEMS_RECEIVED => 'info',
                        ReturnExchange::STATUS_APPROVED => 'success',
                        ReturnExchange::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        ReturnExchange::STATUS_PENDING => 'Pending',
                        ReturnExchange::STATUS_ITEMS_RECEIVED => 'Items Received',
                        ReturnExchange::STATUS_APPROVED => 'Approved',
                        ReturnExchange::STATUS_REJECTED => 'Rejected',
                        default => $state,
                    }),
                TextColumn::make('reason')
                    ->formatStateUsing(fn (string $state): string => ReturnExchange::REASONS[$state] ?? $state),
                TextColumn::make('refund_amount')
                    ->label('Refund')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('requested_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        ReturnExchange::STATUS_PENDING => 'Pending',
                        ReturnExchange::STATUS_ITEMS_RECEIVED => 'Items Received',
                        ReturnExchange::STATUS_APPROVED => 'Approved',
                        ReturnExchange::STATUS_REJECTED => 'Rejected',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordAction('edit');
    }
}