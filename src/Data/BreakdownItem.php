<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasDescription;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasIcon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasUrl;

class BreakdownItem
{
    use EvaluatesClosures;
    use HasColor;
    use HasDescription;
    use HasIcon;
    use HasUrl;

    protected ?Closure $formatValueUsing = null;

    protected float|int|Closure|null $percentage = null;

    final public function __construct(
        protected string|Closure $label,
        protected float|int|Closure $value = 0,
    ) {}

    public static function make(string|Closure $label, float|int|Closure $value = 0): static
    {
        return new static($label, $value);
    }

    public function formatUsing(?Closure $callback): static
    {
        $this->formatValueUsing = $callback;

        return $this;
    }

    /**
     * Provide an explicit percentage instead of letting the widget compute it
     * from the total of every item.
     */
    public function percentage(float|int|Closure|null $percentage): static
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getLabel(): string
    {
        return (string) $this->evaluate($this->label);
    }

    public function getValue(): float
    {
        return (float) $this->evaluate($this->value);
    }

    public function getFormattedValue(): string
    {
        if ($this->formatValueUsing !== null) {
            return (string) ($this->formatValueUsing)($this->getValue());
        }

        return (string) Number::format($this->getValue(), locale: app()->getLocale());
    }

    public function hasExplicitPercentage(): bool
    {
        return $this->percentage !== null;
    }

    public function getPercentage(?float $total = null): ?float
    {
        if ($this->percentage !== null) {
            return (float) $this->evaluate($this->percentage);
        }

        if ($total === null || $total <= 0.0) {
            return null;
        }

        return ($this->getValue() / $total) * 100;
    }

    public function getFormattedPercentage(?float $total = null): ?string
    {
        $percentage = $this->getPercentage($total);

        if ($percentage === null) {
            return null;
        }

        return (string) Number::percentage($percentage, maxPrecision: 1, locale: app()->getLocale());
    }

    public function getBarWidth(?float $total = null): float
    {
        $percentage = $this->getPercentage($total);

        if ($percentage === null) {
            return 0.0;
        }

        return max(0.0, min(100.0, $percentage));
    }
}
