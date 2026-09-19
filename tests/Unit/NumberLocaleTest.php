<?php

declare(strict_types=1);

use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Data\BreakdownItem;
use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Data\UsageLimit;

it('formats values using the application locale', function (): void {
    app()->setLocale('fr');

    expect(Metric::make('Revenue', 1234.5)->getFormattedValue())
        ->toBe(Number::format(1234.5, locale: 'fr'))
        ->not->toBe(Number::format(1234.5, locale: 'en'));
});

it('formats percentages using the application locale', function (): void {
    app()->setLocale('fr');

    $item = BreakdownItem::make('Direct', 25.0);

    expect($item->getFormattedPercentage(100.0))
        ->toBe(Number::percentage(25.0, maxPrecision: 1, locale: 'fr'))
        ->not->toBe(Number::percentage(25.0, maxPrecision: 1, locale: 'en'));
});

it('keeps English formatting when the application locale is English', function (): void {
    app()->setLocale('en');

    expect(UsageLimit::make('Seats', 1234, 2000)->getFormattedValue())
        ->toBe(Number::format(1234, locale: 'en'));
});
