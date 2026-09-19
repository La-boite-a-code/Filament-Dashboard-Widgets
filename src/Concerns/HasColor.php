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
        return $this->sanitizeColorName($this->evaluate($this->color));
    }

    public function hasColor(): bool
    {
        return filled($this->getColor());
    }

    /**
     * Colour names end up inside a "style" attribute as CSS custom properties.
     * Blade escaping prevents breaking out of the attribute, but it leaves
     * colons, semicolons and parentheses intact, which is enough to append
     * arbitrary declarations. Restrict names to the same character set
     * Filament itself allows, and treat anything left empty as unset so the
     * views fall back to their default colour.
     */
    protected function sanitizeColorName(mixed $color): ?string
    {
        if ($color === null) {
            return null;
        }

        $color = (string) preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $color);

        return $color === '' ? null : $color;
    }
}
