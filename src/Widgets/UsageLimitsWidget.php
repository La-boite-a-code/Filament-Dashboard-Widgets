<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasHeading;
use LaBoiteACode\FilamentDashboardWidgets\Data\UsageLimit;

abstract class UsageLimitsWidget extends DashboardWidget
{
    use HasHeading;

    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.usage-limits-widget';

    /**
     * @return array<int, UsageLimit>
     */
    abstract protected function getLimits(): array;

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $limits = array_values(array_filter(
            $this->getLimits(),
            static fn (mixed $limit): bool => $limit instanceof UsageLimit,
        ));

        return [
            'limits' => $limits,
            'hasData' => $limits !== [],
            'heading' => $this->getHeading(),
            'headingDescription' => $this->getHeadingDescription(),
            'pollingInterval' => $this->getPollingInterval(),
            'emptyStateHeading' => $this->getEmptyStateHeading(),
            'emptyStateDescription' => $this->getEmptyStateDescription(),
            'emptyStateIcon' => $this->getEmptyStateIcon(),
        ];
    }
}
