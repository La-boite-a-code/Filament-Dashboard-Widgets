<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Carbon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasHeading;
use LaBoiteACode\FilamentDashboardWidgets\Data\TimelineEvent;

abstract class TimelineWidget extends DashboardWidget implements HasActions, HasSchemas
{
    use HasHeading;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.timeline-widget';

    /**
     * @return array<int, TimelineEvent>
     */
    abstract protected function getEvents(): array;

    protected function shouldGroupByDay(): bool
    {
        return false;
    }

    protected function getLimit(): ?int
    {
        return null;
    }

    protected function getViewAllUrl(): ?string
    {
        return null;
    }

    protected function shouldOpenViewAllInNewTab(): bool
    {
        return false;
    }

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
     * @return array<int, TimelineEvent>
     */
    protected function resolveEvents(): array
    {
        $events = array_values(array_filter(
            $this->getEvents(),
            static fn (mixed $event): bool => $event instanceof TimelineEvent,
        ));

        $limit = $this->getLimit();

        if ($limit !== null) {
            $events = array_slice($events, 0, $limit);
        }

        return $events;
    }

    protected function dayLabel(?Carbon $timestamp): ?string
    {
        if ($timestamp === null) {
            return null;
        }

        if ($timestamp->isToday()) {
            return __('filament-dashboard-widgets::widgets.timeline.today');
        }

        if ($timestamp->isYesterday()) {
            return __('filament-dashboard-widgets::widgets.timeline.yesterday');
        }

        return $timestamp->translatedFormat('d M Y');
    }

    /**
     * @param  array<int, TimelineEvent>  $events
     * @return array<int, array{label: string|null, events: array<int, TimelineEvent>}>
     */
    protected function buildGroups(array $events): array
    {
        if (! $this->shouldGroupByDay()) {
            return [['label' => null, 'events' => $events]];
        }

        $groups = [];

        foreach ($events as $event) {
            $timestamp = $event->getTimestamp();
            $key = $timestamp?->toDateString() ?? '__none__';

            if (! isset($groups[$key])) {
                $groups[$key] = ['label' => $this->dayLabel($timestamp), 'events' => []];
            }

            $groups[$key]['events'][] = $event;
        }

        return array_values($groups);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $events = $this->resolveEvents();

        return [
            'groups' => $this->buildGroups($events),
            'hasData' => $events !== [],
            'heading' => $this->getHeading(),
            'headingDescription' => $this->getHeadingDescription(),
            'pollingInterval' => $this->getPollingInterval(),
            'emptyStateHeading' => $this->getEmptyStateHeading(),
            'emptyStateDescription' => $this->getEmptyStateDescription(),
            'emptyStateIcon' => $this->getEmptyStateIcon(),
        ];
    }
}
