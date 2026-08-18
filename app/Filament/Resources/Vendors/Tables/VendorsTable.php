<?php

namespace App\Filament\Resources\Vendors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class VendorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Contact Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->label('City')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('totalPayable')
                    ->label('Total Payable')
                    ->money(config('pos.currency.code', 'PKR'))
                    ->getStateUsing(fn (\App\Models\Vendor $record): float => $record->totalPayable())
                    ->sortable(),
                TextColumn::make('totalPaid')
                    ->label('Total Paid')
                    ->money(config('pos.currency.code', 'PKR'))
                    ->getStateUsing(fn (\App\Models\Vendor $record): float => $record->totalPaid())
                    ->sortable(),
                TextColumn::make('balance')
                    ->label('Balance')
                    ->money(config('pos.currency.code', 'PKR'))
                    ->getStateUsing(fn (\App\Models\Vendor $record): float => $record->balance())
                    ->color(fn (float $state): string => $state > 0 ? 'danger' : 'success')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}