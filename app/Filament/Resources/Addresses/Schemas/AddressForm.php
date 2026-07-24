<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('company')
                    ->default(null),
                TextInput::make('address_line1')
                    ->required(),
                TextInput::make('address_line2')
                    ->default(null),
                TextInput::make('city')
                    ->required(),
                TextInput::make('state')
                    ->default(null),
                TextInput::make('postal_code')
                    ->required(),
                TextInput::make('country')
                    ->required()
                    ->default('United States'),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                Toggle::make('is_default_billing')
                    ->required(),
                Toggle::make('is_default_shipping')
                    ->required(),
            ]);
    }
}
