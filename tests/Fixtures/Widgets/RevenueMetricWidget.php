<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\MetricWidget;

class RevenueMetricWidget extends MetricWidget
{
    protected function getMetric(): Metric
    {
        return Metric::make('Monthly revenue', 24_850)
            ->formatUsing(fn (int $value): string => Number::currency($value, 'EUR', 'en'))
            ->description('Compared to last month')
            ->trend(12.4)
            ->icon('heroicon-o-banknotes')
            ->color('success')
            ->sparkline([12, 14, 13, 18, 20, 19, 24])
            ->url('https://example.test/orders');
    }
}
