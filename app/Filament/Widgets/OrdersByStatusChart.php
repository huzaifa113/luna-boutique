<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Support\DashboardPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class OrdersByStatusChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Orders by Status';

    protected function getData(): array
    {
        $period = $this->pageFilters['period'] ?? '30d';
        [$from, $to] = DashboardPeriod::range($period);

        $counts = Order::query()
            ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('created_at', '<=', $to))
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $labels = [];
        $data = [];
        $colors = [];

        $colorMap = [
            Order::STATUS_PENDING => '#f59e0b',
            Order::STATUS_DISPATCHED => '#3b82f6',
            Order::STATUS_DELIVERED => '#22c55e',
            Order::STATUS_RETURNED => '#ef4444',
        ];

        foreach (Order::STATUSES as $status) {
            $labels[] = ucfirst($status);
            $data[] = (int) ($counts[$status] ?? 0);
            $colors[] = $colorMap[$status] ?? '#6b7280';
        }

        return [
            'datasets' => [[
                'label' => 'Orders',
                'data' => $data,
                'backgroundColor' => $colors,
                'borderColor' => $colors,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}