<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\RecentItem;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\RecentItemsWidget;

class RecentOrdersWidget extends RecentItemsWidget
{
    protected ?string $heading = 'Recent orders';

    protected function getViewAllUrl(): ?string
    {
        return 'https://example.test/orders';
    }

    protected function getItems(): array
    {
        return [
            RecentItem::make('#1001', 'Ada Lovelace')
                ->meta('120.00 EUR')
                ->badge('Paid')
                ->badgeColor('success')
                ->icon('heroicon-o-user')
                ->url('https://example.test/orders/1001'),

            RecentItem::make('#1002', 'Alan Turing')
                ->meta('80.00 EUR')
                ->badge('Pending')
                ->badgeColor('warning'),
        ];
    }
}
