<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\BreakdownItem;

it('computes a percentage from a provided total', function (): void {
    $item = BreakdownItem::make('Active', 148);

    expect($item->getPercentage(200.0))->toBe(74.0)
        ->and($item->getBarWidth(200.0))->toBe(74.0)
        ->and($item->getFormattedPercentage(200.0))->toBe('74%');
});

it('prefers an explicit percentage over the computed one', function (): void {
    $item = BreakdownItem::make('Active', 148)->percentage(90);

    expect($item->getPercentage(200.0))->toBe(90.0)
        ->and($item->hasExplicitPercentage())->toBeTrue();
});

it('handles a zero or missing total gracefully', function (): void {
    $item = BreakdownItem::make('Active', 10);

    expect($item->getPercentage(0.0))->toBeNull()
        ->and($item->getBarWidth(0.0))->toBe(0.0)
        ->and($item->getFormattedPercentage(0.0))->toBeNull()
        ->and($item->getPercentage(null))->toBeNull();
});

it('formats the value through a callback', function (): void {
    $item = BreakdownItem::make('Revenue', 1234)
        ->formatUsing(fn (float $value): string => '$'.number_format($value));

    expect($item->getFormattedValue())->toBe('$1,234');
});
