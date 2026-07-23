<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Widgets\TimelineWidget;

class EmptyTimelineWidget extends TimelineWidget
{
    protected function getEvents(): array
    {
        return [];
    }
}
