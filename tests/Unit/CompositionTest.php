<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\Composition;
use LaBoiteACode\FilamentDashboardWidgets\Data\CompositionSlice;

it('exposes labels, values, colour tokens and total', function (): void {
    $composition = Composition::make('Revenue')->slices([
        CompositionSlice::make('Direct', 60)->color('primary'),
        CompositionSlice::make('Partners', 40),
    ]);

    expect($composition->getLabels())->toBe(['Direct', 'Partners'])
        ->and($composition->getValues())->toBe([60.0, 40.0])
        ->and($composition->getColorTokens())->toBe(['primary', null])
        ->and($composition->getTotal())->toBe(100.0)
        ->and($composition->hasSlices())->toBeTrue();
});

it('uses an explicit total when provided', function (): void {
    $composition = Composition::make('Revenue')
        ->slices([CompositionSlice::make('Direct', 10)])
        ->total(50);

    expect($composition->getTotal())->toBe(50.0);
});

it('rejects slices that are not CompositionSlice instances', function (): void {
    Composition::make('Revenue')->slices([['Direct', 1]]);
})->throws(InvalidArgumentException::class);

it('rejects an unsupported chart type', function (): void {
    Composition::make('Revenue')->type('line');
})->throws(InvalidArgumentException::class);
