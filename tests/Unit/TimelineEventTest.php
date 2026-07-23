<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use LaBoiteACode\FilamentDashboardWidgets\Data\TimelineEvent;

afterEach(fn () => Carbon::setTestNow());

it('exposes title, relative time, actor, badge and avatar', function (): void {
    Carbon::setTestNow(Carbon::parse('2026-07-15 12:00:00'));

    $event = TimelineEvent::make('Order paid')
        ->timestamp(Carbon::parse('2026-07-15 10:00:00'))
        ->actor('Ada Lovelace')
        ->badge('Paid')
        ->badgeColor('success')
        ->avatar('https://example.test/a.png');

    expect($event->getTitle())->toBe('Order paid')
        ->and($event->hasTimestamp())->toBeTrue()
        ->and($event->getRelativeTime())->toBe('2 hours ago')
        ->and($event->getActor())->toBe('Ada Lovelace')
        ->and($event->getBadge())->toBe('Paid')
        ->and($event->getBadgeColor())->toBe('success')
        ->and($event->hasAvatar())->toBeTrue();
});

it('has no relative time without a timestamp', function (): void {
    expect(TimelineEvent::make('No date')->hasTimestamp())->toBeFalse()
        ->and(TimelineEvent::make('No date')->getRelativeTime())->toBeNull();
});
