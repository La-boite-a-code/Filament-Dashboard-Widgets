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
use LaBoiteACode\FilamentDashboardWidgets\Data\RecentItem;

abstract class RecentItemsWidget extends DashboardWidget implements HasActions, HasSchemas
{
    use HasHeading;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.recent-items-widget';

    /**
     * @return array<int, RecentItem>
     */
    abstract protected function getItems(): array;

    protected function getLimit(): ?int
    {
        $limit = config('filament-dashboard-widgets.recent_items.limit', 5);

        return $limit === null ? null : (int) $limit;
    }

    /**
     * URL of the global "View all" action. Return a URL to reveal the action.
     */
    protected function getViewAllUrl(): ?string
    {
        return null;
    }

    protected function shouldOpenViewAllInNewTab(): bool
    {
        return false;
    }

    /**
     * Native Filament action rendered in the widget footer. Override it fully
     * for advanced behaviour, or simply override getViewAllUrl().
     */
    public function viewAllAction(): Action
    {
        $url = $this->getViewAllUrl();

        return Action::make('viewAll')
            ->label(__('filament-dashboard-widgets::widgets.actions.view_all'))
            ->link()
            ->icon('heroicon-m-arrow-right')
            ->iconPosition(IconPosition::After)
            ->url($url, shouldOpenInNewTab: $this->shouldOpenViewAllInNewTab())
            ->visible(filled($url));
    }

    /**
     * @return array<int, RecentItem>
     */
    protected function resolveItems(): array
    {
        $items = array_values(array_filter(
            $this->getItems(),
            static fn (mixed $item): bool => $item instanceof RecentItem,
        ));

        $limit = $this->getLimit();

        if ($limit !== null) {
            $items = array_slice($items, 0, $limit);
        }

        return $items;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $items = $this->resolveItems();

        return [
            'items' => $items,
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
