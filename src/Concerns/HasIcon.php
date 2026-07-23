<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Concerns;

use Closure;

trait HasIcon
{
    protected string|Closure|null $icon = null;

    public function icon(string|Closure|null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        $icon = $this->evaluate($this->icon);

        return $icon === null ? null : (string) $icon;
    }

    public function hasIcon(): bool
    {
        return filled($this->getIcon());
    }
}
