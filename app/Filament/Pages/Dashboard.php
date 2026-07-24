<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LatestOrdersTable;
use App\Filament\Widgets\OrdersByStatusChart;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\StoreStatsOverview;
use App\Filament\Widgets\TopProductsWidget;
use App\Support\DashboardPeriod;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('period')
                ->label('Period')
                ->options(DashboardPeriod::OPTIONS)
                ->default('30d')
                ->selectablePlaceholder(false)
                ->native(false),
        ]);
    }

    public function getWidgets(): array
    {
        return [
            StoreStatsOverview::class,
            RevenueChart::class,
            OrdersByStatusChart::class,
            LatestOrdersTable::class,
            TopProductsWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}