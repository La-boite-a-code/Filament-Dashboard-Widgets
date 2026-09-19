<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasIcon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasUrl;

class UsageLimit
{
    use EvaluatesClosures;
    use HasColor;
    use HasIcon;
    use HasUrl;

    protected ?Closure $formatValueUsing = null;

    protected float|int|Closure|null $warnThreshold = null;

    final public function __construct(
        protected string|Closure $label,
        protected float|int|Closure $value = 0,
        protected float|int|Closure $limit = 0,
    ) {}

    public static function make(string|Closure $label, float|int|Closure $value = 0, float|int|Closure $limit = 0): static
    {
        return new static($label, $value, $limit);
    }

    public function formatUsing(?Closure $callback): static
    {
        $this->formatValueUsing = $callback;

        return $this;
    }

    /**
     * Fraction of the limit (0 to 1) above which the row is highlighted as a
     * warning, for example 0.8 for 80 percent.
     */
    public function warnThreshold(float|int|Closure|null $threshold): static
    {
        $this->warnThreshold = $threshold;

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

    public function getLimit(): float
    {
        return (float) $this->evaluate($this->limit);
    }

    public function getPercentage(): float
    {
        $limit = $this->getLimit();

        if ($limit <= 0.0) {
            return $this->getValue() > 0.0 ? 100.0 : 0.0;
        }

        return ($this->getValue() / $limit) * 100;
    }

    public function getBarWidth(): float
    {
        return max(0.0, min(100.0, $this->getPercentage()));
    }

    public function isOverLimit(): bool
    {
        return $this->getValue() > $this->getLimit();
    }

    public function getWarnThreshold(): ?float
    {
        $threshold = $this->evaluate($this->warnThreshold);

        return $threshold === null ? null : (float) $threshold;
    }

    public function getWarnPercentage(): ?float
    {
        $threshold = $this->getWarnThreshold();

        return $threshold === null ? null : max(0.0, min(100.0, $threshold * 100));
    }

    public function isOverThreshold(): bool
    {
        $threshold = $this->getWarnThreshold();

        return $threshold !== null && $this->getPercentage() >= $threshold * 100;
    }

    public function getStatusColor(): string
    {
        if ($this->isOverLimit()) {
            return 'danger';
        }

        if ($this->isOverThreshold()) {
            return 'warning';
        }

        return $this->getColor() ?? 'primary';
    }

    protected function format(float $value): string
    {
        if ($this->formatValueUsing !== null) {
            return (string) ($this->formatValueUsing)($value);
        }

        return (string) Number::format($value, locale: app()->getLocale());
    }

    public function getFormattedValue(): string
    {
        return $this->format($this->getValue());
    }

    public function getFormattedLimit(): string
    {
        return $this->format($this->getLimit());
    }

    public function getFormattedPercentage(): string
    {
        return (string) Number::percentage($this->getPercentage(), maxPrecision: 0, locale: app()->getLocale());
    }
}
