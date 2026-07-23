<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\Trend;
use LaBoiteACode\FilamentDashboardWidgets\Data\TrendPoint;

it('exposes labels and values from its points', function (): void {
    $trend = Trend::make('New customers')
        ->value(184)
        ->points([
            TrendPoint::make('2026-07-01', 18),
            TrendPoint::make('2026-07-02', 24),
        ]);

    expect($trend->getLabels())->toBe(['2026-07-01', '2026-07-02'])
        ->and($trend->getValues())->toBe([18.0, 24.0])
        ->and($trend->hasPoints())->toBeTrue()
        ->and($trend->getFormattedValue())->toBe('184');
});

it('rejects points that are not TrendPoint instances', function (): void {
    Trend::make('Broken')->points([['2026-07-01', 18]]);
})->throws(InvalidArgumentException::class);

it('rejects an unsupported chart type', function (): void {
    Trend::make('Broken')->type('pie');
})->throws(InvalidArgumentException::class);

it('maps the area type to a filled line chart', function (): void {
    $trend = Trend::make('Area')->type('area');

    expect($trend->getType())->toBe('area')
        ->and($trend->getChartType())->toBe('line')
        ->and($trend->isFilled())->toBeTrue();
});

it('resolves comparison direction and colour', function (): void {
    expect(Trend::make('Up')->comparison(16.8)->getComparisonColor())->toBe('success')
        ->and(Trend::make('Down')->comparison(-4.2)->getComparisonColor())->toBe('danger')
        ->and(Trend::make('Down but good')->comparison(-4.2)->lowerIsBetter()->getComparisonColor())->toBe('success')
        ->and(Trend::make('Flat')->comparison(0)->getComparisonDirection())->toBe('neutral');
});
