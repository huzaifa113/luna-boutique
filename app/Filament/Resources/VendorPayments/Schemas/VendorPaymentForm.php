<?php

namespace App\Filament\Resources\VendorPayments\Schemas;

use App\Models\Purchase;
use App\Models\Vendor;
use App\Models\VendorPayment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class VendorPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment')
                    ->columns(2)
                    ->schema([
                        Select::make('vendor_id')
                            ->label('Vendor')
                            ->relationship('vendor', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Vendor $record) => $record->company
                                ? "{$record->name} ({$record->company})"
                                : $record->name)
                            ->searchable(['name', 'company', 'phone'])
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('purchase_id', null))
                            ->helperText(fn (Get $get): ?string => static::balanceHint($get('vendor_id'))),
                        Select::make('purchase_id')
                            ->label('Against Purchase')
                            ->options(function (Get $get): array {
                                $vendorId = $get('vendor_id');
                                if (! $vendorId) {
                                    return [];
                                }

                                return Purchase::query()
                                    ->where('vendor_id', $vendorId)
                                    ->where('status', Purchase::STATUS_CONFIRMED)
                                    ->withSum('vendorPayments', 'amount')
                                    ->get()
                                    ->filter(function (Purchase $purchase) {
                                        // Only show purchases that still have an outstanding balance
                                        return (float) $purchase->total - (float) $purchase->vendor_payments_sum_amount > 0.01;
                                    })
                                    ->sortByDesc('purchase_date')
                                    ->pluck('invoice_number', 'id')
                                    ->all();
                            })
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
                            ->default(VendorPayment::METHOD_CASH)
                            ->options(array_combine(
                                VendorPayment::METHODS,
                                array_map(ucfirst(...), VendorPayment::METHODS),
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
     * Human-readable outstanding balance for the selected vendor.
     */
    protected static function balanceHint(mixed $vendorId): ?string
    {
        $vendor = $vendorId ? Vendor::find($vendorId) : null;

        if (! $vendor) {
            return null;
        }

        $symbol = config('pos.currency.symbol', 'Rs');

        return "Outstanding balance: {$symbol} ".number_format($vendor->balance(), 2);
    }
}
