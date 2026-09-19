<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Data\TimelineEvent;

it('keeps legitimate colour names untouched', function (): void {
    expect(Metric::make('Revenue', 1)->color('success')->getColor())->toBe('success')
        ->and(Metric::make('Revenue', 1)->color('gray-500')->getColor())->toBe('gray-500')
        ->and(Metric::make('Revenue', 1)->color('brand_alt')->getColor())->toBe('brand_alt');
});

it('strips characters that would inject CSS declarations into the style attribute', function (): void {
    $metric = Metric::make('Revenue', 1)
        ->color('x);background-image:url(https://evil.test/beacon');

    expect($metric->getColor())->toMatch('/^[a-zA-Z0-9_-]*$/');
});

it('sanitises badge colours as well', function (): void {
    $event = TimelineEvent::make('Order paid')
        ->badgeColor('y);position:fixed;inset:0');

    expect($event->getBadgeColor())->toMatch('/^[a-zA-Z0-9_-]*$/');
});
