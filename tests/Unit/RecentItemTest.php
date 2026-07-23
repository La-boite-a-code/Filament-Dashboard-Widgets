<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\RecentItem;

it('builds a recent item with all of its attributes', function (): void {
    $item = RecentItem::make('#1001', 'Ada Lovelace')
        ->meta('120.00 EUR')
        ->badge('Paid')
        ->badgeColor('success')
        ->icon('heroicon-o-user')
        ->url('/orders/1001');

    expect($item->getTitle())->toBe('#1001')
        ->and($item->getDescription())->toBe('Ada Lovelace')
        ->and($item->getMeta())->toBe('120.00 EUR')
        ->and($item->getBadge())->toBe('Paid')
        ->and($item->getBadgeColor())->toBe('success')
        ->and($item->hasIcon())->toBeTrue()
        ->and($item->hasAvatar())->toBeFalse()
        ->and($item->getUrl())->toBe('/orders/1001');
});

it('resolves closures at read time', function (): void {
    $item = RecentItem::make(fn (): string => 'Dynamic title')
        ->badge(fn (): string => 'Live');

    expect($item->getTitle())->toBe('Dynamic title')
        ->and($item->getBadge())->toBe('Live');
});

it('reports avatar presence', function (): void {
    $item = RecentItem::make('Jane')->avatar('https://example.test/a.png');

    expect($item->hasAvatar())->toBeTrue()
        ->and($item->getAvatar())->toBe('https://example.test/a.png');
});
