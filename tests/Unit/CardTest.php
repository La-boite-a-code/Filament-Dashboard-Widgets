<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\Card;
use LaBoiteACode\FilamentDashboardWidgets\Data\WidgetAction;

it('defaults to the content variant with no extras', function (): void {
    $card = Card::make('Welcome');

    expect($card->getHeading())->toBe('Welcome')
        ->and($card->getVariant())->toBe('content')
        ->and($card->hasValue())->toBeFalse()
        ->and($card->hasDescription())->toBeFalse()
        ->and($card->hasBadge())->toBeFalse()
        ->and($card->hasActions())->toBeFalse()
        ->and($card->getFormattedValue())->toBeNull();
});

it('exposes heading, description, value, badge and colour', function (): void {
    $card = Card::make(fn (): string => 'Revenue')
        ->variant('stat')
        ->description('This month')
        ->value(24_850)
        ->formatUsing(fn (int $value): string => '$'.number_format($value))
        ->badge('+12%')
        ->badgeColor('success')
        ->icon('heroicon-o-banknotes')
        ->color('primary');

    expect($card->getHeading())->toBe('Revenue')
        ->and($card->getVariant())->toBe('stat')
        ->and($card->getDescription())->toBe('This month')
        ->and($card->hasValue())->toBeTrue()
        ->and($card->getFormattedValue())->toBe('$24,850')
        ->and($card->getBadge())->toBe('+12%')
        ->and($card->getBadgeColor())->toBe('success')
        ->and($card->getIcon())->toBe('heroicon-o-banknotes')
        ->and($card->getColor())->toBe('primary');
});

it('formats a numeric value without a formatter', function (): void {
    expect(Card::make('Users')->value(1234)->getFormattedValue())->toBe('1,234');
});

it('holds a list of widget actions', function (): void {
    $card = Card::make('Get started')
        ->variant('cta')
        ->actions([
            WidgetAction::make('Create')->url('/create')->color('primary'),
            WidgetAction::make('Docs')->url('/docs'),
        ]);

    expect($card->hasActions())->toBeTrue()
        ->and($card->getActions())->toHaveCount(2)
        ->and($card->getActions()[0]->getLabel())->toBe('Create')
        ->and($card->getActions()[0]->getColor())->toBe('primary');
});

it('rejects an unsupported variant', function (): void {
    Card::make('Nope')->variant('carousel');
})->throws(InvalidArgumentException::class);

it('rejects actions that are not WidgetAction instances', function (): void {
    Card::make('Nope')->actions(['Create']);
})->throws(InvalidArgumentException::class);
