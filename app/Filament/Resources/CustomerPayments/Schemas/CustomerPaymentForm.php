<?php

namespace App\Filament\Resources\CustomerPayments\Schemas;

use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\Sale;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CustomerPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment')
                    ->columns(2)
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Customer $record) => $record->phone
                                ? "{$record->name} ({$record->phone})"
                                : $record->name)
                            ->searchable(['name', 'phone'])
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('sale_id', null))
                            ->helperText(fn (Get $get): ?string => static::balanceHint($get('customer_id'))),
                        Select::make('sale_id')
                            ->label('Against Invoice')
                            ->options(fn (Get $get): array => $get('customer_id')
                                ? Sale::query()
                                    ->where('customer_id', $get('customer_id'))
                                    ->where('status', Sale::STATUS_COMPLETED)
                                    ->whereIn('payment_status', [Sale::PAYMENT_STATUS_UNPAID, Sale::PAYMENT_STATUS_PARTIAL])
                                    ->orderByDesc('sale_date')
                                    ->pluck('invoice_number', 'id')
                                    ->all()
                                : [])
                            ->searchable()
                            ->helperText('Optional. Leave empty for a general on-account payment.'),
                        DatePicker::make('payment_date')
                            ->required()
                            ->default(now())
                            ->maxDate(now()),
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->minValue(0.01)
                            ->prefix(config('pos.currency.symbol', 'Rs')),
                        Select::make('method')
                            ->required()
                            ->default(CustomerPayment::METHOD_CASH)
                            ->options(array_combine(
                                CustomerPayment::METHODS,
                                array_map(ucfirst(...), CustomerPayment::METHODS),
                            )),
                        TextInput::make('reference_no')
                            ->label('Reference / Cheque No.')
                            ->maxLength(255),
                        Textarea::make('notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * Human-readable outstanding balance for the selected customer.
     */
    protected static function balanceHint(mixed $customerId): ?string
    {
        $customer = $customerId ? Customer::find($customerId) : null;

        if (! $customer) {
            return null;
        }

        $symbol = config('pos.currency.symbol', 'Rs');

        return "Outstanding balance: {$symbol} ".number_format($customer->balance(), 2);
    }
}
