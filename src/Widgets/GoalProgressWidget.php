<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Goal;

abstract class GoalProgressWidget extends DashboardWidget
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.goal-progress-widget';

    abstract protected function getGoal(): Goal;

    protected function hasData(Goal $goal): bool
    {
        return $goal->getTarget() > 0.0;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $goal = $this->getGoal();

        return [
            'goal' => $goal,
            'hasData' => $this->hasData($goal),
            'pollingInterval' => $this->getPollingInterval(),
            'emptyStateHeading' => $this->getEmptyStateHeading(),
            'emptyStateDescription' => $this->getEmptyStateDescription(),
            'emptyStateIcon' => $this->getEmptyStateIcon(),
        ];
    }
}
