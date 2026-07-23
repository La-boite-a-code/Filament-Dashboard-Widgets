<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use BackedEnum;
use Filament\Support\Facades\FilamentColor;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use LaBoiteACode\FilamentDashboardWidgets\Data\ChartSeries;

abstract class ComparisonChartWidget extends ChartWidget
{
    /**
     * Polling is disabled by default, matching the rest of the package rather
     * than Filament's five second ChartWidget default.
     */
    protected ?string $pollingInterval = null;

    /**
     * @return array<int, ChartSeries>
     */
    abstract protected function getSeries(): array;

    /**
     * The shared x-axis labels for every series.
     *
     * @return array<int, string>
     */
    abstract protected function getLabels(): array;

    /**
     * @return array<int, ChartSeries>
     */
    protected function resolveSeries(): array
    {
        return array_values(array_filter(
            $this->getSeries(),
            static fn (mixed $series): bool => $series instanceof ChartSeries,
        ));
    }

    protected function getType(): string
    {
        return ($this->resolveSeries()[0] ?? null)?->getType() ?? 'line';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $series = $this->resolveSeries();
        $labels = $this->getLabels();

        if ($series === [] || $labels === []) {
            return [];
        }

        $datasets = [];

        foreach ($series as $index => $item) {
            $color = $this->resolveSeriesColor($item->getColor(), $index);
            $type = $item->getType();

            $dataset = [
                'label' => $item->getLabel(),
                'data' => $item->getValues(),
                'type' => $type,
            ];

            if ($type === 'bar') {
                $dataset['backgroundColor'] = $color;
            } else {
                $dataset['borderColor'] = $color;
                // A filled area uses a translucent tint so it sits under the
                // line instead of flooding the card with a solid slab; this
                // reads well in both light and dark themes.
                $dataset['backgroundColor'] = $item->isFilled()
                    ? 'color-mix(in srgb, '.$color.' 18%, transparent)'
                    : $color;
                $dataset['fill'] = $item->isFilled() ? 'start' : false;
            }

            $datasets[] = $dataset;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    /**
     * Resolve a series colour token to a concrete Filament colour value for the
     * canvas. Tokens always come from the panel's registered colours, so
     * nothing is hard coded. Series without a colour cycle the default palette.
     */
    protected function resolveSeriesColor(?string $token, int $index): string
    {
        $registered = FilamentColor::getColors();

        $cycle = array_values(array_filter(
            ['primary', 'success', 'warning', 'info', 'danger', 'gray'],
            static fn (string $name): bool => isset($registered[$name]),
        ));

        if ($cycle === []) {
            $cycle = array_keys($registered);
        }

        if ($cycle === []) {
            $cycle = ['primary'];
        }

        $name = ($token !== null && isset($registered[$token]))
            ? $token
            : $cycle[$index % count($cycle)];

        $shades = $registered[$name] ?? [];

        return (string) ($shades[500] ?? (reset($shades) ?: '#6b7280'));
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
            ?? 'heroicon-o-presentation-chart-line';
    }
}
