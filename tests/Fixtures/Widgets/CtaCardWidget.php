<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Card;
use LaBoiteACode\FilamentDashboardWidgets\Data\WidgetAction;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\CardWidget;

class CtaCardWidget extends CardWidget
{
    protected function getCard(): Card
    {
        return Card::make('Invite your team')
            ->variant('cta')
            ->icon('heroicon-o-user-plus')
            ->description('Collaborate by adding your colleagues to the workspace.')
            ->color('primary')
            ->actions([
                WidgetAction::make('Send invites')->url('/team/invite')->icon('heroicon-m-paper-airplane'),
                WidgetAction::make('Learn more')->url('/docs/team'),
            ]);
    }
}
