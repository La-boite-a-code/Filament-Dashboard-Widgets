<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasHeading;
use LaBoiteACode\FilamentDashboardWidgets\Data\BreakdownItem;

abstract class SegmentBarWidget extends DashboardWidget
{
    use HasHeading;

    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.segment-bar-widget';

    /**
     * @return array<int, BreakdownItem>
     */
    abstract protected function getSegments(): array;

    /**
     * Provide an explicit total. When it is larger than the sum of the
     * segments, the remainder is shown as headroom on the track.
     */
    protected function getTotal(): ?float
    {
        return null;
    }

    /**
     * @param  array<int, BreakdownItem>  $segments
     */
    protected function resolveTotal(array $segments): float
    {
        $total = $this->getTotal();

        if ($total !== null) {
            return $total;
        }

        return array_sum(array_map(
            static fn (BreakdownItem $segment): float => $segment->getValue(),
            $segments,
        ));
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $segments = array_values(array_filter(
            $this->getSegments(),
            static fn (mixed $segment): bool => $segment instanceof BreakdownItem,
        ));

        return [
            'segments' => $segments,
            'total' => $this->resolveTotal($segments),
            'hasData' => $segments !== [],
            'heading' => $this->getHeading(),
            'headingDescription' => $this->getHeadingDescription(),
            'pollingInterval' => $this->getPollingInterval(),
            'emptyStateHeading' => $this->getEmptyStateHeading(),
            'emptyStateDescription' => $this->getEmptyStateDescription(),
            'emptyStateIcon' => $this->getEmptyStateIcon(),
        ];
    }
}
