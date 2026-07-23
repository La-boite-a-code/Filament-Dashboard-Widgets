<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Concerns;

use Closure;

trait HasUrl
{
    protected string|Closure|null $url = null;

    protected bool|Closure $shouldOpenUrlInNewTab = false;

    public function url(string|Closure|null $url, bool|Closure $shouldOpenInNewTab = false): static
    {
        $this->url = $url;
        $this->shouldOpenUrlInNewTab = $shouldOpenInNewTab;

        return $this;
    }

    public function openUrlInNewTab(bool|Closure $condition = true): static
    {
        $this->shouldOpenUrlInNewTab = $condition;

        return $this;
    }

    public function getUrl(): ?string
    {
        $url = $this->evaluate($this->url);

        return filled($url) ? (string) $url : null;
    }

    public function shouldOpenUrlInNewTab(): bool
    {
        return (bool) $this->evaluate($this->shouldOpenUrlInNewTab);
    }

    public function hasUrl(): bool
    {
        return $this->getUrl() !== null;
    }
}
