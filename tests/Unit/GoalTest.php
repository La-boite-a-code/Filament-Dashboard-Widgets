<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\Goal;

it('computes percentage, remaining and bar width', function (): void {
    $goal = Goal::make('Monthly goal', current: 72_500, target: 100_000);

    expect($goal->getPercentage())->toBe(72.5)
        ->and($goal->getRemaining())->toBe(27_500.0)
        ->and($goal->getBarWidth())->toBe(72.5)
        ->and($goal->isReached())->toBeFalse();
});

it('guards against a zero or negative target', function (): void {
    expect(Goal::make('No target', current: 10, target: 0)->getPercentage())->toBe(100.0)
        ->and(Goal::make('No target', current: 0, target: 0)->getPercentage())->toBe(0.0)
        ->and(Goal::make('No target', current: 0, target: 0)->getBarWidth())->toBe(0.0);
});

it('caps progress at 100 by default but keeps the real percentage', function (): void {
    $goal = Goal::make('Overshot', current: 145, target: 100);

    expect($goal->getPercentage())->toBe(145.0)
        ->and($goal->getBarWidth())->toBe(100.0)
        ->and($goal->getDisplayPercentage())->toBe(100.0)
        ->and($goal->isExceeded())->toBeTrue()
        ->and($goal->getOverflow())->toBe(45.0);
});

it('exposes the real percentage when overflow is allowed', function (): void {
    $goal = Goal::make('Overshot', current: 145, target: 100)->allowOverflow();

    expect($goal->getDisplayPercentage())->toBe(145.0)
        ->and($goal->getBarWidth())->toBe(100.0);
});

it('handles negative progress explicitly', function (): void {
    $goal = Goal::make('Negative', current: -20, target: 100);

    expect($goal->getPercentage())->toBe(-20.0)
        ->and($goal->getBarWidth())->toBe(0.0)
        ->and($goal->getRemaining())->toBe(120.0);
});

it('reports its status and overdue state', function (): void {
    expect(Goal::make('Reached', current: 100, target: 100)->getStatus())->toBe('reached')
        ->and(Goal::make('Exceeded', current: 120, target: 100)->getStatus())->toBe('exceeded')
        ->and(Goal::make('In progress', current: 40, target: 100)->getStatus())->toBe('in_progress');

    $overdue = Goal::make('Late', current: 40, target: 100)->deadline(now()->subDay());

    expect($overdue->isOverdue())->toBeTrue()
        ->and($overdue->getStatus())->toBe('overdue');
});

it('formats values through the callback', function (): void {
    $goal = Goal::make('Revenue', current: 500, target: 1_000)
        ->formatUsing(fn (float $value): string => '$'.number_format($value));

    expect($goal->getFormattedCurrent())->toBe('$500')
        ->and($goal->getFormattedTarget())->toBe('$1,000')
        ->and($goal->getFormattedRemaining())->toBe('$500');
});
