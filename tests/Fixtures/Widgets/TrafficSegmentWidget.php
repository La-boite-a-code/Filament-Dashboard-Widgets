<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\BreakdownItem;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\SegmentBarWidget;

class TrafficSegmentWidget extends SegmentBarWidget
{
    protected ?string $heading = 'Traffic by source';

    protected function getSegments(): array
    {
        return [
            BreakdownItem::make('Organic', 5_400)->color('success'),
            BreakdownItem::make('Direct', 2_600)->color('primary'),
            BreakdownItem::make('Referral', 1_200)->color('warning'),
            BreakdownItem::make('Social', 800)->color('gray'),
        ];
    }
}
