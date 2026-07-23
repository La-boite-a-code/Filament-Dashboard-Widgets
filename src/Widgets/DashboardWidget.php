<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use Filament\Widgets\Widget;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\CanPoll;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasEmptyState;

/**
 * Shared base for the package widgets that render a custom Blade view. It wires
 * the common polling and empty state behaviour on top of Filament's native
 * Widget class, without duplicating anything Filament already provides.
 */
abstract class DashboardWidget extends Widget
{
    use CanPoll;
    use HasEmptyState;
}
