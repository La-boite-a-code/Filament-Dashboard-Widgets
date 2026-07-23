<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\VarianceItem;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\VarianceWidget;

class TopMoversVarianceWidget extends VarianceWidget
{
    protected ?string $heading = 'Top movers this week';

    protected function shouldSortByChange(): bool
    {
        return true;
    }

    protected function getItems(): array
    {
        return [
            VarianceItem::make('Enterprise', 3_200)->previous(2_600),
            VarianceItem::make('SMB', 1_800)->previous(2_100),
            VarianceItem::make('Startup', 900)->previous(600),
        ];
    }
}
