<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Concerns;

use Closure;

trait HasColor
{
    protected string|Closure|null $color = null;

    public function color(string|Closure|null $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        $color = $this->evaluate($this->color);

        return $color === null ? null : (string) $color;
    }

    public function hasColor(): bool
    {
        return filled($this->getColor());
    }
}
