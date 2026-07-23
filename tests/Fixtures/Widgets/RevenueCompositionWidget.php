<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Data\Composition;
use LaBoiteACode\FilamentDashboardWidgets\Data\CompositionSlice;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\CompositionWidget;

class RevenueCompositionWidget extends CompositionWidget
{
    protected function getComposition(): Composition
    {
        return Composition::make('Revenue by channel')
            ->type('doughnut')
            ->formatUsing(fn (float $value): string => Number::currency($value, 'EUR', 'en'))
            ->slices([
                CompositionSlice::make('Direct', 12_000)->color('primary'),
                CompositionSlice::make('Marketplace', 8_000)->color('success'),
                CompositionSlice::make('Partners', 5_000)->color('warning'),
            ]);
    }
}
