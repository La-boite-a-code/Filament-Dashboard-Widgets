<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\UsageLimit;

it('computes percentage, bar width and status', function (): void {
    expect(UsageLimit::make('Seats', 42, 50)->getPercentage())->toBe(84.0)
        ->and(UsageLimit::make('Seats', 42, 50)->warnThreshold(0.8)->getStatusColor())->toBe('warning')
        ->and(UsageLimit::make('Seats', 30, 50)->warnThreshold(0.8)->getStatusColor())->toBe('primary')
        ->and(UsageLimit::make('Seats', 30, 50)->warnThreshold(0.8)->color('info')->getStatusColor())->toBe('info')
        ->and(UsageLimit::make('Seats', 60, 50)->getStatusColor())->toBe('danger')
        ->and(UsageLimit::make('Seats', 60, 50)->getBarWidth())->toBe(100.0)
        ->and(UsageLimit::make('Seats', 60, 50)->isOverLimit())->toBeTrue()
        ->and(UsageLimit::make('Seats', 10, 0)->getPercentage())->toBe(100.0);
});

it('formats the value, limit and percentage', function (): void {
    $limit = UsageLimit::make('Storage', 380, 500)
        ->formatUsing(fn (float $value): string => number_format($value).' GB');

    expect($limit->getFormattedValue())->toBe('380 GB')
        ->and($limit->getFormattedLimit())->toBe('500 GB')
        ->and($limit->getFormattedPercentage())->toBe('76%')
        ->and($limit->getWarnPercentage())->toBeNull();
});
