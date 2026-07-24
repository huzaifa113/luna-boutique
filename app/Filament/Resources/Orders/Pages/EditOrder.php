<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('verify_payment')
                ->label('Verify & Mark as Paid')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn ($record) => $record
                    && $record->payment_status === Order::PAYMENT_STATUS_PENDING
                    && $record->payment_method === Order::PAYMENT_METHOD_ONLINE)
                ->requiresConfirmation()
                ->modalDescription('Confirm that you have verified the customer\'s transaction details before marking this order as paid.')
                ->action(function ($record) {
                    $record->update(['payment_status' => Order::PAYMENT_STATUS_PAID]);

                    Notification::make()
                        ->title('Payment verified')
                        ->body("Order {$record->order_number} has been marked as paid.")
                        ->success()
                        ->send();

                    $this->refreshFormData(['payment_status']);
                }),

            DeleteAction::make(),
        ];
    }
}
