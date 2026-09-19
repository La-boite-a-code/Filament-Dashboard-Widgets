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

class Metric
{
    use EvaluatesClosures;
    use HasColor;
    use HasDescription;
    use HasIcon;
    use HasUrl;

    protected ?Closure $formatValueUsing = null;

    protected float|int|null $trend = null;

    protected string|Closure|null $trendLabel = null;

    protected bool $lowerIsBetter = false;

    /** @var array<int, int|float> */
    protected array $sparkline = [];

    protected string|Closure|null $tooltip = null;

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

    public function trend(float|int|null $trend): static
    {
        $this->trend = $trend;

        return $this;
    }

    public function trendLabel(string|Closure|null $label): static
    {
        $this->trendLabel = $label;

        return $this;
    }

    public function lowerIsBetter(bool $condition = true): static
    {
        $this->lowerIsBetter = $condition;

        return $this;
    }

    /**
     * @param  array<int, int|float>  $values
     */
    public function sparkline(array $values): static
    {
        $this->sparkline = array_values($values);

        return $this;
    }

    public function tooltip(string|Closure|null $tooltip): static
    {
        $this->tooltip = $tooltip;

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
        return $this->getValue() !== null;
    }

    public function getFormattedValue(): string
    {
        $value = $this->getValue();

        if ($this->formatValueUsing !== null) {
            return (string) ($this->formatValueUsing)($value);
        }

        if (is_int($value) || is_float($value)) {
            return (string) Number::format($value, locale: app()->getLocale());
        }

        return (string) $value;
    }

    public function getTrend(): float|int|null
    {
        return $this->trend;
    }

    public function hasTrend(): bool
    {
        return $this->trend !== null;
    }

    public function getTrendDirection(): string
    {
        if ($this->trend === null || (float) $this->trend === 0.0) {
            return 'neutral';
        }

        return $this->trend > 0 ? 'up' : 'down';
    }

    public function isLowerBetter(): bool
    {
        return $this->lowerIsBetter;
    }

    public function getTrendColor(): string
    {
        return match ($this->getTrendDirection()) {
            'up' => $this->lowerIsBetter ? 'danger' : 'success',
            'down' => $this->lowerIsBetter ? 'success' : 'danger',
            default => 'gray',
        };
    }

    public function getTrendIcon(): string
    {
        return match ($this->getTrendDirection()) {
            'up' => 'heroicon-m-arrow-trending-up',
            'down' => 'heroicon-m-arrow-trending-down',
            default => 'heroicon-m-arrows-right-left',
        };
    }

    public function getTrendLabel(): ?string
    {
        if ($this->trendLabel !== null) {
            $label = $this->evaluate($this->trendLabel);

            return $label === null ? null : (string) $label;
        }

        if ($this->trend === null) {
            return null;
        }

        $formatted = Number::percentage(abs((float) $this->trend), maxPrecision: 2, locale: app()->getLocale());
        $sign = $this->trend > 0 ? '+' : ($this->trend < 0 ? '-' : '');

        return $sign.$formatted;
    }

    /**
     * @return array<int, int|float>
     */
    public function getSparkline(): array
    {
        return $this->sparkline;
    }

    public function hasSparkline(): bool
    {
        return count($this->sparkline) >= 2;
    }

    public function getTooltip(): ?string
    {
        $tooltip = $this->evaluate($this->tooltip);

        return $tooltip === null ? null : (string) $tooltip;
    }

    /**
     * Build a normalized polyline point string within a 0..100 coordinate box,
     * ready to be dropped into an inline SVG. Returns null when there are not
     * enough points to draw a line.
     */
    public function getSparklinePoints(int $maxPoints = 30): ?string
    {
        $values = $this->sparkline;

        if (count($values) > $maxPoints) {
            $values = array_slice($values, -$maxPoints);
        }

        $count = count($values);

        if ($count < 2) {
            return null;
        }

        $min = min($values);
        $max = max($values);
        $range = ($max - $min) ?: 1;
        $stepX = 100 / ($count - 1);

        $points = [];

        foreach (array_values($values) as $index => $value) {
            $x = round($index * $stepX, 2);
            $y = round(100 - (($value - $min) / $range) * 100, 2);
            $points[] = "{$x},{$y}";
        }

        return implode(' ', $points);
    }
}
