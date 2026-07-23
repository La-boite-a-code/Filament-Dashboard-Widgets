<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Trend;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\TrendWidget;

class EmptyTrendWidget extends TrendWidget
{
    protected function getTrend(): Trend
    {
        return Trend::make('Signups');
    }
}
