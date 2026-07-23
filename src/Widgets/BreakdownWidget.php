<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\IconPosition;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasHeading;
use LaBoiteACode\FilamentDashboardWidgets\Data\BreakdownItem;

abstract class BreakdownWidget extends DashboardWidget implements HasActions, HasSchemas
{
    use HasHeading;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.breakdown-widget';

    /**
     * @return array<int, BreakdownItem>
     */
    abstract protected function getItems(): array;

    /**
     * Provide an explicit total instead of summing the item values.
     */
    protected function getTotal(): ?float
    {
        return null;
    }

    /**
     * Sort the items by descending value. Disabled by default so the order
     * defined by the developer is preserved.
     */
    protected function shouldSortByValue(): bool
    {
        return false;
    }

    /**
     * Maximum number of items to display before the "View more" action.
     */
    protected function getLimit(): ?int
    {
        $limit = config('filament-dashboard-widgets.breakdown.limit');

        return $limit === null ? null : (int) $limit;
    }

    protected function getViewMoreUrl(): ?string
    {
        return null;
    }

    protected function shouldOpenViewMoreInNewTab(): bool
    {
        return false;
    }

    public function viewMoreAction(): Action
    {
        $url = $this->getViewMoreUrl();

        return Action::make('viewMore')
            ->label(__('filament-dashboard-widgets::widgets.actions.view_more'))
            ->link()
            ->icon('heroicon-m-arrow-right')
            ->iconPosition(IconPosition::After)
            ->url($url, shouldOpenInNewTab: $this->shouldOpenViewMoreInNewTab())
            ->visible(filled($url));
    }

    /**
     * @param  array<int, BreakdownItem>  $items
     */
    protected function resolveTotal(array $items): float
    {
        $total = $this->getTotal();

        if ($total !== null) {
            return $total;
        }

        return array_sum(array_map(
            static fn (BreakdownItem $item): float => $item->getValue(),
            $items,
        ));
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $items = array_values(array_filter(
            $this->getItems(),
            static fn (mixed $item): bool => $item instanceof BreakdownItem,
        ));

        if ($this->shouldSortByValue()) {
            usort($items, static fn (BreakdownItem $a, BreakdownItem $b): int => $b->getValue() <=> $a->getValue());
        }

        $total = $this->resolveTotal($items);

        $limit = $this->getLimit();
        $hiddenCount = 0;

        if ($limit !== null && count($items) > $limit) {
            $hiddenCount = count($items) - $limit;
            $items = array_slice($items, 0, $limit);
        }

        return [
            'items' => $items,
            'total' => $total,
            'hasData' => $items !== [],
            'hiddenCount' => $hiddenCount,
            'showBars' => (bool) config('filament-dashboard-widgets.breakdown.show_bars', true),
            'heading' => $this->getHeading(),
            'headingDescription' => $this->getHeadingDescription(),
            'pollingInterval' => $this->getPollingInterval(),
            'emptyStateHeading' => $this->getEmptyStateHeading(),
            'emptyStateDescription' => $this->getEmptyStateDescription(),
            'emptyStateIcon' => $this->getEmptyStateIcon(),
        ];
    }
}
