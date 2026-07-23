<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\TimelineEvent;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\TimelineWidget;

class ActivityTimelineWidget extends TimelineWidget
{
    protected ?string $heading = 'Recent activity';

    protected function shouldGroupByDay(): bool
    {
        return true;
    }

    protected function getViewAllUrl(): ?string
    {
        return 'https://example.test/activity';
    }

    protected function getEvents(): array
    {
        return [
            TimelineEvent::make('Order #1042 paid')
                ->description('by Ada Lovelace')
                ->timestamp(now()->subHours(2))
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->badge('Paid')
                ->badgeColor('success'),

            TimelineEvent::make('New signup')
                ->timestamp(now()->subHours(5))
                ->icon('heroicon-o-user-plus')
                ->color('primary'),

            TimelineEvent::make('Invoice sent')
                ->timestamp(now()->subDay())
                ->icon('heroicon-o-envelope')
                ->actor('System'),
        ];
    }
}
