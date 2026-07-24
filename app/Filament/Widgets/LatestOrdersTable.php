<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Support\DashboardPeriod;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrdersTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $period = $this->pageFilters['period'] ?? '30d';
        [$from, $to] = DashboardPeriod::range($period);

        return $table
            ->heading('Latest Orders')
            ->query(
                Order::query()
                    ->with('user')
                    ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
                    ->when($to, fn ($q) => $q->where('created_at', '<=', $to))
                    ->latest()
            )
            ->columns([
                TextColumn::make('order_number')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Order::STATUS_PENDING => 'warning',
                        Order::STATUS_DISPATCHED => 'info',
                        Order::STATUS_DELIVERED => 'success',
                        Order::STATUS_RETURNED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Order::PAYMENT_STATUS_PENDING => 'warning',
                        Order::PAYMENT_STATUS_PAID => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('total')
                    ->money('USD'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(5)
            ->recordUrl(fn ($record) => OrderResource::getUrl('edit', ['record' => $record]));
    }
}