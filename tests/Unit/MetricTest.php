<?php

declare(strict_types=1);

use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;

it('builds a metric with a formatted value', function (): void {
    $metric = Metric::make('Monthly revenue', 24_850)
        ->formatUsing(fn (int $value): string => Number::currency($value, 'EUR', 'en'));

    expect($metric->getLabel())->toBe('Monthly revenue')
        ->and($metric->getValue())->toBe(24_850)
        ->and($metric->getFormattedValue())->toContain('24,850');
});

it('resolves trend direction, colour and icon', function (): void {
    expect(Metric::make('Up', 1)->trend(12.4)->getTrendDirection())->toBe('up')
        ->and(Metric::make('Up', 1)->trend(12.4)->getTrendColor())->toBe('success')
        ->and(Metric::make('Down', 1)->trend(-3.2)->getTrendDirection())->toBe('down')
        ->and(Metric::make('Down', 1)->trend(-3.2)->getTrendColor())->toBe('danger')
        ->and(Metric::make('Flat', 1)->trend(0)->getTrendDirection())->toBe('neutral')
        ->and(Metric::make('None', 1)->getTrendColor())->toBe('gray');
});

it('inverts trend semantics when lower is better', function (): void {
    $metric = Metric::make('Error rate', 1)->trend(-8.0)->lowerIsBetter();

    expect($metric->getTrendDirection())->toBe('down')
        ->and($metric->getTrendColor())->toBe('success');
});

it('builds normalized sparkline points and caps the number of points', function (): void {
    $metric = Metric::make('Series', 10)->sparkline([0, 5, 10]);

    expect($metric->hasSparkline())->toBeTrue()
        ->and($metric->getSparklinePoints())->toBe('0,100 50,50 100,0');

    $long = Metric::make('Series', 10)->sparkline(range(1, 100));
    $points = $long->getSparklinePoints(maxPoints: 30);

    expect(substr_count((string) $points, ' '))->toBe(29);
});

it('supports non numeric values without a trend', function (): void {
    $metric = Metric::make('Status', 'Operational');

    expect($metric->getFormattedValue())->toBe('Operational')
        ->and($metric->hasTrend())->toBeFalse();
});
