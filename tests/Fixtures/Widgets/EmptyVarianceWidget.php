<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Widgets\VarianceWidget;

class EmptyVarianceWidget extends VarianceWidget
{
    protected function getItems(): array
    {
        return [];
    }
}
