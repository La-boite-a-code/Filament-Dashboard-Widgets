<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\MetricWidget;

class PollingMetricWidget extends MetricWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getMetric(): Metric
    {
        return Metric::make('Live visitors', 42);
    }
}
