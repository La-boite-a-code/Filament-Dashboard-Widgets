<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Widgets\ComparisonChartWidget;

class EmptyComparisonChartWidget extends ComparisonChartWidget
{
    protected ?string $heading = 'Revenue vs target';

    protected function getLabels(): array
    {
        return [];
    }

    protected function getSeries(): array
    {
        return [];
    }
}
