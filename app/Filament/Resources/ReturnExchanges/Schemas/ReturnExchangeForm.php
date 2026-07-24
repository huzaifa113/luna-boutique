<?php

namespace App\Filament\Resources\ReturnExchanges\Schemas;

use App\Models\ReturnExchange;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReturnExchangeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Request Details')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('user_name')
                            ->label('Customer')
                            ->content(fn ($record) => $record?->user?->name),

                        Placeholder::make('order_number')
                            ->label('Order')
                            ->content(fn ($record) => $record?->order?->order_number),

                        Placeholder::make('status')
                            ->label('Status')
                            ->content(fn ($record) => ucfirst($record?->status ?? '')),

                        Placeholder::make('reason')
                            ->label('Reason')
                            ->content(fn ($record) => ReturnExchange::REASONS[$record?->reason] ?? $record?->reason),

                        Textarea::make('details')
                            ->label('Customer Details')
                            ->readOnly()
                            ->columnSpanFull(),
                    ]),

                Section::make('Returned Items')
                    ->schema([
                        Repeater::make('items')
                            ->label('')
                            ->relationship('items')
                            ->disabled()
                            ->deletable(false)
                            ->addable(false)
                            ->reorderable(false)
                            ->columns(4)
                            ->schema([
                                Placeholder::make('product_name')
                                    ->label('Product')
                                    ->content(fn ($record) => $record?->orderItem?->product?->name ?? $record?->orderItem?->product_name ?? 'N/A'),

                                Placeholder::make('product_sku')
                                    ->label('SKU')
                                    ->content(fn ($record) => $record?->orderItem?->product?->sku ?? $record?->orderItem?->product_sku ?? 'N/A'),

                                Placeholder::make('quantity')
                                    ->label('Qty')
                                    ->content(fn ($record) => $record?->quantity),

                                Placeholder::make('total_price')
                                    ->label('Total')
                                    ->content(fn ($record) => $record?->total_price ? '$' . number_format($record->total_price, 2) : '—'),
                            ]),
                    ]),

                Section::make('Attachments')
                    ->schema([
                        Repeater::make('attachments')
                            ->label('')
                            ->relationship('attachments')
                            ->disabled()
                            ->deletable(false)
                            ->addable(false)
                            ->reorderable(false)
                            ->columns(2)
                            ->schema([
                                Placeholder::make('original_name')
                                    ->label('File')
                                    ->content(fn ($record) => $record?->original_name ?? 'N/A'),

                                Placeholder::make('path')
                                    ->label('Preview')
                                    ->html()
                                    ->content(fn ($record) => $record?->path
                                        ? '<a href="' . asset('storage/' . $record->path) . '" target="_blank" class="text-primary-600 underline">View File</a>'
                                        : '—'),
                            ]),
                    ]),

                Section::make('Admin Actions')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Update Status')
                            ->options([
                                ReturnExchange::STATUS_PENDING => 'Pending',
                                ReturnExchange::STATUS_ITEMS_RECEIVED => 'Items Received',
                                ReturnExchange::STATUS_APPROVED => 'Approved',
                                ReturnExchange::STATUS_REJECTED => 'Rejected',
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, $record, $set) {
                                if ($state === ReturnExchange::STATUS_APPROVED || $state === ReturnExchange::STATUS_REJECTED) {
                                    $set('admin_processed_at', now());
                                }
                            }),

                        Placeholder::make('refund_amount')
                            ->label('Refund Amount')
                            ->content(fn ($record) => $record?->refund_amount ? '$' . number_format($record->refund_amount, 2) : '—'),

                        Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
