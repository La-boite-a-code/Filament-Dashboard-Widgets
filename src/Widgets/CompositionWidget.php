<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use BackedEnum;
use Filament\Support\Facades\FilamentColor;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use LaBoiteACode\FilamentDashboardWidgets\Data\Composition;

abstract class CompositionWidget extends ChartWidget
{
    /**
     * Polling is disabled by default, matching the rest of the package rather
     * than Filament's five second ChartWidget default.
     */
    protected ?string $pollingInterval = null;

    abstract protected function getComposition(): Composition;

    protected function getType(): string
    {
        return $this->getComposition()->getType();
    }

    public function getHeading(): string|Htmlable|null
    {
        return $this->getComposition()->getLabel();
    }

    public function getDescription(): string|Htmlable|null
    {
        $composition = $this->getComposition();

        if (! $composition->hasSlices()) {
            return null;
        }

        return trans('filament-dashboard-widgets::widgets.composition.total', [
            'value' => $composition->getFormattedTotal(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $composition = $this->getComposition();

        if (! $composition->hasSlices()) {
            return [];
        }

        return [
            'datasets' => [
                [
                    'label' => $composition->getLabel(),
                    'data' => $composition->getValues(),
                    'backgroundColor' => $this->resolveColors($composition),
                ],
            ],
            'labels' => $composition->getLabels(),
        ];
    }

    /**
     * Resolve each slice colour token to a concrete Filament colour value so it
     * can be painted on the canvas. Tokens always come from the panel's
     * registered colours, so nothing is hard coded. Slices without a colour
     * cycle through the default palette.
     *
     * @return array<int, string>
     */
    protected function resolveColors(Composition $composition): array
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

        $resolved = [];

        foreach ($composition->getColorTokens() as $index => $token) {
            $name = ($token !== null && isset($registered[$token]))
                ? $token
                : $cycle[$index % count($cycle)];

            $shades = $registered[$name] ?? [];

            $resolved[] = (string) ($shades[500] ?? (reset($shades) ?: '#6b7280'));
        }

        return $resolved;
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
            ?? 'heroicon-o-chart-pie';
    }
}
