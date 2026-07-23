<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Card;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\CardWidget;

class IconCardWidget extends CardWidget
{
    protected function getCard(): Card
    {
        return Card::make('API documentation')
            ->variant('icon')
            ->description('Explore the reference and guides')
            ->icon('heroicon-o-book-open')
            ->color('info')
            ->url('https://example.test/docs');
    }
}
