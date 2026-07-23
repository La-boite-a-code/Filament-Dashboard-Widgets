<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;

abstract class MetricWidget extends DashboardWidget
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.metric-widget';

    abstract protected function getMetric(): Metric;

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $metric = $this->getMetric();

        return [
            'metric' => $metric,
            'hasData' => $metric->hasValue(),
            'showTrendIcon' => (bool) config('filament-dashboard-widgets.metric.show_trend_icon', true),
            'sparklineMaxPoints' => (int) config('filament-dashboard-widgets.metric.sparkline_max_points', 30),
            'sparklineHeight' => (int) config('filament-dashboard-widgets.metric.sparkline_height', 40),
            'pollingInterval' => $this->getPollingInterval(),
            'emptyStateHeading' => $this->getEmptyStateHeading(),
            'emptyStateDescription' => $this->getEmptyStateDescription(),
            'emptyStateIcon' => $this->getEmptyStateIcon(),
        ];
    }
}
