<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Concerns;

use Closure;

trait HasDescription
{
    protected string|Closure|null $description = null;

    public function description(string|Closure|null $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        $description = $this->evaluate($this->description);

        return $description === null ? null : (string) $description;
    }

    public function hasDescription(): bool
    {
        return filled($this->getDescription());
    }
}
