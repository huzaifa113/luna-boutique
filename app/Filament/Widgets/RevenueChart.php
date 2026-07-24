<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Support\DashboardPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RevenueChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Revenue';

    protected function getData(): array
    {
        $period = $this->pageFilters['period'] ?? '30d';
        [$from, $to] = DashboardPeriod::range($period);

        $rows = Order::query()
            ->where('payment_status', Order::PAYMENT_STATUS_PAID)
            ->where('status', '!=', Order::STATUS_RETURNED)
            ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('created_at', '<=', $to))
            ->selectRaw('DATE(created_at) as d, SUM(total) as revenue')
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        return [
            'datasets' => [[
                'label' => 'Revenue',
                'data'  => $rows->pluck('revenue')->map(fn ($v) => round((float) $v, 2)),
                'fill'  => 'start',
            ]],
            'labels' => $rows->pluck('d'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}