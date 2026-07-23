<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Card;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\CardWidget;

class StatCardWidget extends CardWidget
{
    protected function getCard(): Card
    {
        return Card::make('Monthly revenue')
            ->variant('stat')
            ->value(24_850)
            ->formatUsing(fn (int $value): string => '$'.number_format($value))
            ->description('Compared to last month')
            ->badge('+12.4%')
            ->badgeColor('success')
            ->icon('heroicon-o-banknotes')
            ->color('primary');
    }
}
