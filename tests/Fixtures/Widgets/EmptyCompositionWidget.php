<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Composition;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\CompositionWidget;

class EmptyCompositionWidget extends CompositionWidget
{
    protected function getComposition(): Composition
    {
        return Composition::make('Revenue by channel');
    }
}
