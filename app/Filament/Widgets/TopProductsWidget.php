<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Support\DashboardPeriod;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Top Products')
            ->records(function (): array {
                $period = $this->pageFilters['period'] ?? '30d';
                [$from, $to] = DashboardPeriod::range($period);

                return OrderItem::query()
                    ->select([
                        'product_name',
                        DB::raw('SUM(quantity) as total_units'),
                        DB::raw('SUM(total_price) as total_revenue'),
                    ])
                    ->whereHas('order', function ($q) use ($from, $to) {
                        $q->when($from, fn ($q) => $q->where('created_at', '>=', $from))
                          ->when($to, fn ($q) => $q->where('created_at', '<=', $to));
                    })
                    ->groupBy('product_name')
                    ->orderByDesc(DB::raw('SUM(total_price)'))
                    ->get()
                    ->map(fn ($item) => [
                        'product_name' => $item->product_name,
                        'total_units' => (int) $item->total_units,
                        'total_revenue' => (float) $item->total_revenue,
                    ])
                    ->toArray();
            })
            ->columns([
                TextColumn::make('product_name')
                    ->label('Product')
                    ->searchable(),
                TextColumn::make('total_units')
                    ->label('Units Sold')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_revenue')
                    ->label('Revenue')
                    ->money('USD')
                    ->sortable(),
            ]);
    }
}