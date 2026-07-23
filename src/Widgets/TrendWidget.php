<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use BackedEnum;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use LaBoiteACode\FilamentDashboardWidgets\Data\Trend;

abstract class TrendWidget extends ChartWidget
{
    /**
     * Polling is disabled by default, unlike Filament's ChartWidget which
     * polls every five seconds. Set this property or override the global
     * config to enable it.
     */
    protected ?string $pollingInterval = null;

    abstract protected function getTrend(): Trend;

    protected function getType(): string
    {
        return $this->getTrend()->getChartType();
    }

    public function getHeading(): string|Htmlable|null
    {
        return $this->getTrend()->getLabel();
    }

    public function getDescription(): string|Htmlable|null
    {
        $trend = $this->getTrend();

        $parts = [];

        if ($trend->hasValue()) {
            $parts[] = $trend->getFormattedValue();
        }

        if ($trend->hasComparison()) {
            $parts[] = trim(
                $trend->getComparisonLabel().' '.__('filament-dashboard-widgets::widgets.trend.comparison')
            );
        }

        return $parts === [] ? null : implode(' - ', $parts);
    }

    public function getColor(): string
    {
        return $this->getTrend()->getColor() ?? 'primary';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $trend = $this->getTrend();

        if (! $trend->hasPoints()) {
            return [];
        }

        return [
            'datasets' => [
                [
                    'label' => $trend->getLabel(),
                    'data' => $trend->getValues(),
                    'fill' => $trend->isFilled() ? 'start' : false,
                ],
            ],
            'labels' => $trend->getLabels(),
        ];
    }

    protected function getPollingInterval(): ?string
    {
        return $this->pollingInterval ?? config('filament-dashboard-widgets.polling_interval');
    }

    public function getEmptyStateHeading(): string|Htmlable
    {
        return config('filament-dashboard-widgets.empty_state.heading')
            ?? __('filament-dashboard-widgets::widgets.empty_state.heading');
    }

    public function getEmptyStateDescription(): string|Htmlable|null
    {
        return config('filament-dashboard-widgets.empty_state.description');
    }

    public function getEmptyStateIcon(): string|BackedEnum|Htmlable
    {
        return config('filament-dashboard-widgets.empty_state.icon')
            ?? 'heroicon-o-circle-stack';
    }
}
