<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Widgets\SegmentBarWidget;

class EmptySegmentBarWidget extends SegmentBarWidget
{
    protected function getSegments(): array
    {
        return [];
    }
}
