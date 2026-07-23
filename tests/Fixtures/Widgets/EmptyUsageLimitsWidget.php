<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Widgets\UsageLimitsWidget;

class EmptyUsageLimitsWidget extends UsageLimitsWidget
{
    protected function getLimits(): array
    {
        return [];
    }
}
