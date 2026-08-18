<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('city')
                            ->maxLength(255)
                            ->datalist(fn () => Customer::query()
                                ->whereNotNull('city')
                                ->distinct()
                                ->orderBy('city')
                                ->pluck('city')
                                ->all()),
                        Select::make('user_id')
                            ->label('Linked Online Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Optional. Links this POS customer to their online store account.'),
                        Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Account')
                    ->columns(2)
                    ->schema([
                        TextInput::make('opening_balance')
                            ->helperText('Amount already receivable from this customer before the system went live.')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->prefix(config('pos.currency.symbol', 'Rs')),
                        TextInput::make('credit_limit')
                            ->numeric()
                            ->prefix(config('pos.currency.symbol', 'Rs')),
                        Toggle::make('is_active')
                            ->default(true)
                            ->inline(false),
                        Textarea::make('notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
