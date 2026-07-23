<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasHeading;
use LaBoiteACode\FilamentDashboardWidgets\Data\VarianceItem;

abstract class VarianceWidget extends DashboardWidget
{
    use HasHeading;

    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.variance-widget';

    /**
     * @return array<int, VarianceItem>
     */
    abstract protected function getItems(): array;

    /**
     * Sort the items by descending absolute change so the biggest movers come
     * first. Disabled by default to preserve the developer's order.
     */
    protected function shouldSortByChange(): bool
    {
        return false;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $items = array_values(array_filter(
            $this->getItems(),
            static fn (mixed $item): bool => $item instanceof VarianceItem,
        ));

        if ($this->shouldSortByChange()) {
            usort($items, static fn (VarianceItem $a, VarianceItem $b): int => abs($b->getChange()) <=> abs($a->getChange()));
        }

        $maxAbsoluteChange = 0.0;

        foreach ($items as $item) {
            $maxAbsoluteChange = max($maxAbsoluteChange, abs($item->getChange()));
        }

        return [
            'items' => $items,
            'maxAbsoluteChange' => $maxAbsoluteChange,
            'hasData' => $items !== [],
            'heading' => $this->getHeading(),
            'headingDescription' => $this->getHeadingDescription(),
            'pollingInterval' => $this->getPollingInterval(),
            'emptyStateHeading' => $this->getEmptyStateHeading(),
            'emptyStateDescription' => $this->getEmptyStateDescription(),
            'emptyStateIcon' => $this->getEmptyStateIcon(),
        ];
    }
}
