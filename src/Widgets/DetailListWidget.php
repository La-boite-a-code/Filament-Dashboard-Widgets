<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasHeading;
use LaBoiteACode\FilamentDashboardWidgets\Data\Detail;

abstract class DetailListWidget extends DashboardWidget
{
    use HasHeading;

    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.detail-list-widget';

    /**
     * @return array<int, Detail>
     */
    abstract protected function getDetails(): array;

    /**
     * @return array<int, Detail>
     */
    protected function resolveDetails(): array
    {
        return array_values(array_filter(
            $this->getDetails(),
            static fn (mixed $detail): bool => $detail instanceof Detail,
        ));
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $details = $this->resolveDetails();

        return [
            'details' => $details,
            'hasData' => $details !== [],
            'heading' => $this->getHeading(),
            'headingDescription' => $this->getHeadingDescription(),
            'pollingInterval' => $this->getPollingInterval(),
            'emptyStateHeading' => $this->getEmptyStateHeading(),
            'emptyStateDescription' => $this->getEmptyStateDescription(),
            'emptyStateIcon' => $this->getEmptyStateIcon(),
        ];
    }
}
