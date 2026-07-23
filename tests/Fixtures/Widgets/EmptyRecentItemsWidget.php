<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Widgets\RecentItemsWidget;

class EmptyRecentItemsWidget extends RecentItemsWidget
{
    protected function getItems(): array
    {
        return [];
    }
}
