<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\MetricWidget;

class EmptyMetricWidget extends MetricWidget
{
    protected function getMetric(): Metric
    {
        return Metric::make('Revenue', null);
    }
}
