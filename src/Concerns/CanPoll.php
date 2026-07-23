<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Concerns;

trait CanPoll
{
    protected ?string $pollingInterval = null;

    public function pollingInterval(?string $interval): static
    {
        $this->pollingInterval = $interval;

        return $this;
    }

    /**
     * Resolve the Livewire polling interval for the widget. The per widget
     * value wins, otherwise the global package default is used. A null result
     * keeps polling disabled, which is the default behaviour.
     */
    public function getPollingInterval(): ?string
    {
        $interval = $this->pollingInterval ?? config('filament-dashboard-widgets.polling_interval');

        return $interval === null ? null : (string) $interval;
    }
}
