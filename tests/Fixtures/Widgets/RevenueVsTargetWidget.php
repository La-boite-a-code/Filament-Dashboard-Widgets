<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\ChartSeries;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\ComparisonChartWidget;

class RevenueVsTargetWidget extends ComparisonChartWidget
{
    protected ?string $heading = 'Revenue vs target';

    protected function getLabels(): array
    {
        return ['Jan', 'Feb', 'Mar', 'Apr'];
    }

    protected function getSeries(): array
    {
        return [
            ChartSeries::make('Actual')->values([12, 18, 15, 22])->color('primary')->filled(),
            ChartSeries::make('Target')->values([14, 16, 18, 20])->color('gray'),
        ];
    }
}
