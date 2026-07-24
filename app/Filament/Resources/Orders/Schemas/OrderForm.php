<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('order_number')
                    ->required(),
                TextInput::make('shipping_address_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('billing_address_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('coupon_id')
                    ->numeric()
                    ->default(null),
                Select::make('status')
                    ->required()
                    ->default(Order::STATUS_PENDING)
                    ->options([
                        Order::STATUS_PENDING => 'Pending',
                        Order::STATUS_DISPATCHED => 'Dispatched',
                        Order::STATUS_DELIVERED => 'Delivered',
                        Order::STATUS_RETURNED => 'Returned',
                    ]),
                Select::make('payment_status')
                    ->required()
                    ->default(Order::PAYMENT_STATUS_PENDING)
                    ->options([
                        Order::PAYMENT_STATUS_PENDING => 'Pending',
                        Order::PAYMENT_STATUS_PAID => 'Paid',
                    ]),
                Select::make('payment_method')
                    ->default(null)
                    ->options([
                        Order::PAYMENT_METHOD_ONLINE => 'Online Payment',
                        Order::PAYMENT_METHOD_CASH => 'Cash on Delivery',
                    ]),
                Section::make('Payment Verification')
                    ->description('Details submitted by the customer for online payment. Verify before marking the order as paid.')
                    ->schema([
                        TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->default(null),
                        Select::make('payment_channel')
                            ->label('Payment Through')
                            ->default(null)
                            ->options([
                                Order::PAYMENT_CHANNEL_EASYPAISA => 'Easypaisa',
                                Order::PAYMENT_CHANNEL_JAZZCASH => 'JazzCash',
                                Order::PAYMENT_CHANNEL_BANK => 'Bank Account',
                            ]),
                        FileUpload::make('payment_screenshot')
                            ->label('Transaction Screenshot')
                            ->image()
                            ->disk('public')
                            ->directory('payment_proofs')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('payment_method') === Order::PAYMENT_METHOD_ONLINE),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('shipping')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}