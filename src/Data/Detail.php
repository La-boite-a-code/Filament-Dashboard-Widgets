<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasIcon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasUrl;

class Detail
{
    use EvaluatesClosures;
    use HasColor;
    use HasIcon;
    use HasUrl;

    protected ?Closure $formatValueUsing = null;

    protected string|Closure|null $badge = null;

    protected string|Closure|null $badgeColor = null;

    protected string|Closure|null $placeholder = null;

    final public function __construct(
        protected string|Closure $label,
        protected mixed $value = null,
    ) {}

    public static function make(string|Closure $label, mixed $value = null): static
    {
        return new static($label, $value);
    }

    public function formatUsing(?Closure $callback): static
    {
        $this->formatValueUsing = $callback;

        return $this;
    }

    public function badge(string|Closure|null $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    public function badgeColor(string|Closure|null $color): static
    {
        $this->badgeColor = $color;

        return $this;
    }

    /**
     * Text rendered when the value is null or empty, instead of leaving the row
     * blank. Falls back to the translated default when not set.
     */
    public function placeholder(string|Closure|null $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getLabel(): string
    {
        return (string) $this->evaluate($this->label);
    }

    public function getValue(): mixed
    {
        return $this->evaluate($this->value);
    }

    public function hasValue(): bool
    {
        $value = $this->getValue();

        return $value !== null && $value !== '';
    }

    public function getFormattedValue(): string
    {
        if (! $this->hasValue()) {
            $placeholder = $this->evaluate($this->placeholder);

            return $placeholder === null
                ? __('filament-dashboard-widgets::widgets.detail.placeholder')
                : (string) $placeholder;
        }

        $value = $this->getValue();

        if ($this->formatValueUsing !== null) {
            return (string) ($this->formatValueUsing)($value);
        }

        if (is_int($value) || is_float($value)) {
            return (string) Number::format($value);
        }

        return (string) $value;
    }

    public function getBadge(): ?string
    {
        $badge = $this->evaluate($this->badge);

        return $badge === null ? null : (string) $badge;
    }

    public function hasBadge(): bool
    {
        return filled($this->getBadge());
    }

    public function getBadgeColor(): ?string
    {
        $color = $this->evaluate($this->badgeColor);

        return $color === null ? null : (string) $color;
    }
}
