<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\ChartSeries;

it('exposes a label, float values, a type and the fill flag', function (): void {
    $series = ChartSeries::make('Actual')
        ->values([12, 18.5, 15])
        ->type('bar')
        ->filled()
        ->color('primary');

    expect($series->getLabel())->toBe('Actual')
        ->and($series->getValues())->toBe([12.0, 18.5, 15.0])
        ->and($series->getType())->toBe('bar')
        ->and($series->isFilled())->toBeTrue()
        ->and($series->getColor())->toBe('primary');
});

it('defaults to a line series that is not filled', function (): void {
    $series = ChartSeries::make('Target')->values([1, 2, 3]);

    expect($series->getType())->toBe('line')
        ->and($series->isFilled())->toBeFalse()
        ->and($series->getColor())->toBeNull();
});

it('resolves a closure label', function (): void {
    $series = ChartSeries::make(fn (): string => 'Forecast');

    expect($series->getLabel())->toBe('Forecast');
});

it('rejects an unsupported series type', function (): void {
    ChartSeries::make('Actual')->type('doughnut');
})->throws(InvalidArgumentException::class);
