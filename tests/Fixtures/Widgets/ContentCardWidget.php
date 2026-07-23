<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Card;
use LaBoiteACode\FilamentDashboardWidgets\Data\WidgetAction;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\CardWidget;

class ContentCardWidget extends CardWidget
{
    protected function getCard(): Card
    {
        return Card::make('Release notes')
            ->variant('content')
            ->icon('heroicon-o-megaphone')
            ->badge('New')
            ->badgeColor('success')
            ->description('Version 2.0 ships multi series charts, usage meters and cards.')
            ->color('primary')
            ->actions([
                WidgetAction::make('Read more')->url('https://example.test/changelog'),
            ]);
    }
}
