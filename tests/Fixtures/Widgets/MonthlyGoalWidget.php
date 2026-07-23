<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Data\Goal;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\GoalProgressWidget;

class MonthlyGoalWidget extends GoalProgressWidget
{
    protected function getGoal(): Goal
    {
        return Goal::make('Monthly goal', current: 72_500, target: 100_000)
            ->formatUsing(fn (float $value): string => Number::currency($value, 'EUR', 'en'))
            ->color('primary')
            ->showRemaining()
            ->showPercentage();
    }
}
