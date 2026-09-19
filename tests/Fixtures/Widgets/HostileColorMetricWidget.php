<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\MetricWidget;

/**
 * Simulates a host application that feeds untrusted input into a colour
 * setter. The rendered markup must never carry the injected declarations.
 */
class HostileColorMetricWidget extends MetricWidget
{
    protected function getMetric(): Metric
    {
        return Metric::make('Revenue', 1_000)
            ->color('x);background-image:url(https://evil.test/beacon');
    }
}
