<?php

namespace App\Support;

use Carbon\CarbonImmutable;

class DashboardPeriod
{
    public const OPTIONS = [
        'today'     => 'Today',
        'yesterday' => 'Yesterday',
        '7d'        => 'Last 7 days',
        '30d'       => 'Last 30 days',
        'month'     => 'This month',
        'year'      => 'This year',
        'all'       => 'All time',
    ];

    /**
     * @return array{0: ?CarbonImmutable, 1: ?CarbonImmutable} [from, to]  (null = unbounded)
     */
    public static function range(?string $key, ?string $from = null, ?string $to = null): array
    {
        $now = CarbonImmutable::now();

        return match ($key) {
            'today'     => [$now->startOfDay(), $now->endOfDay()],
            'yesterday' => [$now->subDay()->startOfDay(), $now->subDay()->endOfDay()],
            '7d'        => [$now->subDays(6)->startOfDay(), $now->endOfDay()],
            '30d'       => [$now->subDays(29)->startOfDay(), $now->endOfDay()],
            'month'     => [$now->startOfMonth(), $now->endOfMonth()],
            'year'      => [$now->startOfYear(), $now->endOfYear()],
            'custom'    => [
                $from ? CarbonImmutable::parse($from)->startOfDay() : null,
                $to ? CarbonImmutable::parse($to)->endOfDay() : null,
            ],
            default     => [null, null], // 'all'
        };
    }

    /** Previous comparable period, for trend/delta arrows. */
    public static function previousRange(?string $key): array
    {
        $now = CarbonImmutable::now();
        return match ($key) {
            'today'     => [$now->subDay()->startOfDay(), $now->subDay()->endOfDay()],
            'yesterday' => [$now->subDays(2)->startOfDay(), $now->subDays(2)->endOfDay()],
            '7d'        => [$now->subDays(13)->startOfDay(), $now->subDays(7)->endOfDay()],
            '30d'       => [$now->subDays(59)->startOfDay(), $now->subDays(30)->endOfDay()],
            'month'     => [$now->subMonth()->startOfMonth(), $now->subMonth()->endOfMonth()],
            'year'      => [$now->subYear()->startOfYear(), $now->subYear()->endOfYear()],
            default     => [null, null],
        };
    }
}