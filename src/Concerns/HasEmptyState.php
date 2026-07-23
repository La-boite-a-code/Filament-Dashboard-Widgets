<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Concerns;

trait HasEmptyState
{
    protected ?string $emptyStateHeading = null;

    protected ?string $emptyStateDescription = null;

    protected ?string $emptyStateIcon = null;

    public function emptyStateHeading(?string $heading): static
    {
        $this->emptyStateHeading = $heading;

        return $this;
    }

    public function emptyStateDescription(?string $description): static
    {
        $this->emptyStateDescription = $description;

        return $this;
    }

    public function emptyStateIcon(?string $icon): static
    {
        $this->emptyStateIcon = $icon;

        return $this;
    }

    public function getEmptyStateHeading(): string
    {
        return $this->emptyStateHeading
            ?? config('filament-dashboard-widgets.empty_state.heading')
            ?? __('filament-dashboard-widgets::widgets.empty_state.heading');
    }

    public function getEmptyStateDescription(): ?string
    {
        return $this->emptyStateDescription
            ?? config('filament-dashboard-widgets.empty_state.description');
    }

    public function getEmptyStateIcon(): string
    {
        return $this->emptyStateIcon
            ?? config('filament-dashboard-widgets.empty_state.icon')
            ?? 'heroicon-o-circle-stack';
    }
}
