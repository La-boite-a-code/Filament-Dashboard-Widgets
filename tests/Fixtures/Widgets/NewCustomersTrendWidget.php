<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Trend;
use LaBoiteACode\FilamentDashboardWidgets\Data\TrendPoint;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\TrendWidget;

class NewCustomersTrendWidget extends TrendWidget
{
    protected function getTrend(): Trend
    {
        return Trend::make('New customers')
            ->value(184)
            ->comparison(16.8)
            ->points([
                TrendPoint::make('2026-07-01', 18),
                TrendPoint::make('2026-07-02', 24),
                TrendPoint::make('2026-07-03', 21),
            ])
            ->type('area')
            ->color('primary');
    }
}
