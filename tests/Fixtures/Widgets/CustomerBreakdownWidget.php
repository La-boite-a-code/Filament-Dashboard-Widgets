<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\BreakdownItem;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\BreakdownWidget;

class CustomerBreakdownWidget extends BreakdownWidget
{
    protected ?string $heading = 'Customers by status';

    protected function getItems(): array
    {
        return [
            BreakdownItem::make('Active', 148)
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            BreakdownItem::make('Pending', 32)
                ->color('warning'),

            BreakdownItem::make('Inactive', 20)
                ->color('gray'),
        ];
    }
}
