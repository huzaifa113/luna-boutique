<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Support\DashboardPeriod;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $period = $this->pageFilters['period'] ?? '30d';
        [$from, $to] = DashboardPeriod::range($period);

        $base = fn () => Order::query()
            ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('created_at', '<=', $to));

        // Current period earnings
        $earnings = (float) (clone $base())
            ->where('payment_status', Order::PAYMENT_STATUS_PAID)
            ->where('status', '!=', Order::STATUS_RETURNED)
            ->sum('total');

        // Previous period for deltas
        [$prevFrom, $prevTo] = DashboardPeriod::previousRange($period);
        $prevEarnings = 0;
        if ($prevFrom !== null && $period !== 'all') {
            $prevEarnings = (float) Order::query()
                ->where('payment_status', Order::PAYMENT_STATUS_PAID)
                ->where('status', '!=', Order::STATUS_RETURNED)
                ->where('created_at', '>=', $prevFrom)
                ->where('created_at', '<=', $prevTo)
                ->sum('total');
        }

        $earningsDelta = $prevEarnings > 0 ? round((($earnings - $prevEarnings) / $prevEarnings) * 100, 1) : 0;
        $earningsTrend = $earningsDelta >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $earningsColor = $earningsDelta >= 0 ? 'success' : 'danger';

        $countFor = fn (string $status) => (clone $base())->where('status', $status)->count();

        // Compute sparkline for earnings (7 daily buckets)
        $sparkline = [];
        if ($from && $to) {
            $days = min(7, (int) ceil($from->diffInDays($to)) + 1);
            for ($i = $days - 1; $i >= 0; $i--) {
                $dayStart = $to->copy()->subDays($i)->startOfDay();
                $dayEnd = $to->copy()->subDays($i)->endOfDay();
                $dayEarnings = (float) Order::query()
                    ->where('payment_status', Order::PAYMENT_STATUS_PAID)
                    ->where('status', '!=', Order::STATUS_RETURNED)
                    ->where('created_at', '>=', $dayStart)
                    ->where('created_at', '<=', $dayEnd)
                    ->sum('total');
                $sparkline[] = $dayEarnings;
            }
        }

        $totalOrders = (clone $base())->count();
        $paidOrders = (clone $base())
            ->where('payment_status', Order::PAYMENT_STATUS_PAID)
            ->where('status', '!=', Order::STATUS_RETURNED)
            ->count();
        $avgOrderValue = $paidOrders > 0 ? $earnings / $paidOrders : 0;

        $makeDescription = fn ($current, $prev, $label) => $prev > 0
            ? ($label . ' ' . ($current >= 0 ? '▲' : '▼') . ' ' . abs(round((($current - $prev) / $prev) * 100, 1)) . '% vs previous')
            : $label;

        return [
            Stat::make('Earnings', '$' . number_format($earnings, 2))
                ->description($makeDescription($earnings, $prevEarnings, 'Paid, non-returned orders'))
                ->descriptionIcon($earningsTrend)
                ->color($earningsColor)
                ->chart($sparkline),

            Stat::make('Pending', $countFor(Order::STATUS_PENDING))
                ->description('Awaiting dispatch')
                ->color('warning'),

            Stat::make('Dispatched', $countFor(Order::STATUS_DISPATCHED))
                ->color('info'),

            Stat::make('Delivered', $countFor(Order::STATUS_DELIVERED))
                ->color('success'),

            Stat::make('Returned', $countFor(Order::STATUS_RETURNED))
                ->color('danger'),

            Stat::make('Total orders', $totalOrders)
                ->description('Avg: $' . number_format($avgOrderValue, 2))
                ->color('primary'),
        ];
    }
}