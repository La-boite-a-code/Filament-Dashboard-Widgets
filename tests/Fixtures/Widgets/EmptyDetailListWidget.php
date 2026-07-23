<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Widgets\DetailListWidget;

class EmptyDetailListWidget extends DetailListWidget
{
    protected function getDetails(): array
    {
        return [];
    }
}
