<?php

namespace App\Filament\Resources\ReturnExchanges\Pages;

use App\Filament\Resources\ReturnExchanges\ReturnExchangeResource;
use App\Models\Coupon;
use App\Models\ReturnExchange;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EditReturnExchange extends EditRecord
{
    protected static string $resource = ReturnExchangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve & Generate Refund Coupon')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn ($record) => $record && $record->status === ReturnExchange::STATUS_ITEMS_RECEIVED)
                ->requiresConfirmation()
                ->action(function ($record) {
                    DB::transaction(function () use ($record) {
                        // Updating the status to approved also restocks the returned
                        // items via the ReturnExchange::updated model event.
                        $record->update([
                            'status' => ReturnExchange::STATUS_APPROVED,
                            'admin_processed_at' => now(),
                        ]);

                        $this->generateRefundCoupon($record);
                    });
                }),

            Action::make('reject')
                ->label('Reject')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn ($record) => $record && in_array($record->status, [
                    ReturnExchange::STATUS_PENDING,
                    ReturnExchange::STATUS_ITEMS_RECEIVED,
                ]))
                ->requiresConfirmation()
                ->form([
                    \Filament\Forms\Components\Textarea::make('admin_notes')
                        ->label('Rejection Reason')
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    $record->update([
                        'status' => ReturnExchange::STATUS_REJECTED,
                        'admin_notes' => $data['admin_notes'],
                        'admin_processed_at' => now(),
                    ]);
                }),

            Action::make('mark_received')
                ->label('Mark Items Received')
                ->color('info')
                ->icon('heroicon-o-archive-box')
                ->visible(fn ($record) => $record && $record->status === ReturnExchange::STATUS_PENDING)
                ->action(function ($record) {
                    $record->update(['status' => ReturnExchange::STATUS_ITEMS_RECEIVED]);
                }),
        ];
    }

    protected function generateRefundCoupon(ReturnExchange $returnExchange): void
    {
        $refundAmount = $returnExchange->refund_amount ?? $returnExchange->order->total;

        $coupon = Coupon::create([
            'code' => 'REFUND-' . strtoupper(Str::random(8)),
            'user_id' => $returnExchange->user_id,
            'type' => 'fixed',
            'value' => $refundAmount,
            'min_order_amount' => 0,
            'max_discount' => $refundAmount,
            'usage_limit' => 1,
            'used' => 0,
            'expires_at' => now()->addMonths(6),
            'is_active' => true,
        ]);

        $returnExchange->update([
            'coupon_id' => $coupon->id,
            'refund_amount' => $refundAmount,
        ]);
    }
}