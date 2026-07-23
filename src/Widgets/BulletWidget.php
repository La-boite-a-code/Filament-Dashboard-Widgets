<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Bullet;

abstract class BulletWidget extends DashboardWidget
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.bullet-widget';

    abstract protected function getBullet(): Bullet;

    protected function hasData(Bullet $bullet): bool
    {
        return $bullet->getScaleMax() > 0.0;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $bullet = $this->getBullet();

        return [
            'bullet' => $bullet,
            'hasData' => $this->hasData($bullet),
            'pollingInterval' => $this->getPollingInterval(),
            'emptyStateHeading' => $this->getEmptyStateHeading(),
            'emptyStateDescription' => $this->getEmptyStateDescription(),
            'emptyStateIcon' => $this->getEmptyStateIcon(),
        ];
    }
}
