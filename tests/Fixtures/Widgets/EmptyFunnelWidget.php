<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Widgets\FunnelWidget;

class EmptyFunnelWidget extends FunnelWidget
{
    protected function getStages(): array
    {
        return [];
    }
}
