<?php

namespace App\Filament\Resources\Reviews\Schemas;

use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('product_id')
                    ->label('Product')
                    ->required()
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('order_id')
                    ->label('Order')
                    ->relationship('order', 'order_number')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                ToggleButtons::make('rating')
                    ->required()
                    ->options([
                        1 => '★',
                        2 => '★★',
                        3 => '★★★',
                        4 => '★★★★',
                        5 => '★★★★★',
                    ])
                    ->grouped()
                    ->colors([
                        1 => 'danger',
                        2 => 'warning',
                        3 => 'warning',
                        4 => 'success',
                        5 => 'success',
                    ]),
                TextInput::make('title')
                    ->nullable()
                    ->maxLength(255),
                Textarea::make('comment')
                    ->required()
                    ->maxLength(2000)
                    ->columnSpanFull(),
                ToggleButtons::make('status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->colors([
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    ])
                    ->default('pending'),
            ]);
    }
}
