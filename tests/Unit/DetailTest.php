<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\Detail;

it('formats a value through a callback', function (): void {
    $detail = Detail::make('MRR', 4900)
        ->formatUsing(fn (int $value): string => '$'.number_format($value));

    expect($detail->getLabel())->toBe('MRR')
        ->and($detail->hasValue())->toBeTrue()
        ->and($detail->getFormattedValue())->toBe('$4,900');
});

it('falls back to a placeholder when the value is empty', function (): void {
    expect(Detail::make('Notes', null)->getFormattedValue())->toBe('-')
        ->and(Detail::make('Notes', '')->hasValue())->toBeFalse()
        ->and(Detail::make('Notes', null)->placeholder('Not set')->getFormattedValue())->toBe('Not set');
});

it('exposes an optional badge', function (): void {
    $detail = Detail::make('Status', 'Active')->badge('Active')->badgeColor('success');

    expect($detail->hasBadge())->toBeTrue()
        ->and($detail->getBadge())->toBe('Active')
        ->and($detail->getBadgeColor())->toBe('success');
});
