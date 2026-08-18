<?php

namespace App\Filament\Resources\Vendors\Schemas;

use App\Models\Vendor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vendor Details')
                    ->columns(2)
                    ->schema([
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
                            ->maxLength(255)
                            ->datalist(fn () => Vendor::query()
                                ->whereNotNull('city')
                                ->distinct()
                                ->orderBy('city')
                                ->pluck('city')
                                ->all()),
                        TextInput::make('tax_number')
                            ->label('NTN / Tax Number')
                            ->maxLength(255),
                        Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Account')
                    ->columns(2)
                    ->schema([
                        TextInput::make('opening_balance')
                            ->helperText('Amount already payable to this vendor before the system went live.')
                            ->numeric()
                            ->default(0)
                            ->required()
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
