<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use Illuminate\Support\Number;
use InvalidArgumentException;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;

class Trend
{
    use EvaluatesClosures;
    use HasColor;

    public const array TYPES = ['line', 'bar', 'area'];

    protected mixed $value = null;

    protected ?Closure $formatValueUsing = null;

    protected float|int|null $comparison = null;

    protected string|Closure|null $comparisonLabel = null;

    protected bool $lowerIsBetter = false;

    /** @var array<int, TrendPoint> */
    protected array $points = [];

    protected string $type = 'line';

    final public function __construct(
        protected string|Closure $label,
    ) {}

    public static function make(string|Closure $label): static
    {
        return new static($label);
    }

    public function value(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function formatUsing(?Closure $callback): static
    {
        $this->formatValueUsing = $callback;

        return $this;
    }

    public function comparison(float|int|null $comparison): static
    {
        $this->comparison = $comparison;

        return $this;
    }

    public function comparisonLabel(string|Closure|null $label): static
    {
        $this->comparisonLabel = $label;

        return $this;
    }

    public function lowerIsBetter(bool $condition = true): static
    {
        $this->lowerIsBetter = $condition;

        return $this;
    }

    /**
     * @param  array<int, TrendPoint>  $points
     */
    public function points(array $points): static
    {
        foreach ($points as $point) {
            if (! $point instanceof TrendPoint) {
                throw new InvalidArgumentException(
                    'Trend points must be instances of '.TrendPoint::class.'.'
                );
            }
        }

        $this->points = array_values($points);

        return $this;
    }

    public function type(string $type): static
    {
        if (! in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException(
                "Unsupported trend type [{$type}]. Supported types are: ".implode(', ', self::TYPES).'.'
            );
        }

        $this->type = $type;

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

    public function getFormattedValue(): ?string
    {
        $value = $this->getValue();

        if ($value === null) {
            return null;
        }

        if ($this->formatValueUsing !== null) {
            return (string) ($this->formatValueUsing)($value);
        }

        if (is_int($value) || is_float($value)) {
            return (string) Number::format($value, locale: app()->getLocale());
        }

        return (string) $value;
    }

    public function getComparison(): float|int|null
    {
        return $this->comparison;
    }

    public function hasComparison(): bool
    {
        return $this->comparison !== null;
    }

    public function getComparisonDirection(): string
    {
        if ($this->comparison === null || (float) $this->comparison === 0.0) {
            return 'neutral';
        }

        return $this->comparison > 0 ? 'up' : 'down';
    }

    public function getComparisonColor(): string
    {
        return match ($this->getComparisonDirection()) {
            'up' => $this->lowerIsBetter ? 'danger' : 'success',
            'down' => $this->lowerIsBetter ? 'success' : 'danger',
            default => 'gray',
        };
    }

    public function getComparisonIcon(): string
    {
        return match ($this->getComparisonDirection()) {
            'up' => 'heroicon-m-arrow-trending-up',
            'down' => 'heroicon-m-arrow-trending-down',
            default => 'heroicon-m-arrows-right-left',
        };
    }

    public function getComparisonLabel(): ?string
    {
        if ($this->comparisonLabel !== null) {
            $label = $this->evaluate($this->comparisonLabel);

            return $label === null ? null : (string) $label;
        }

        if ($this->comparison === null) {
            return null;
        }

        $formatted = Number::percentage(abs((float) $this->comparison), maxPrecision: 2, locale: app()->getLocale());
        $sign = $this->comparison > 0 ? '+' : ($this->comparison < 0 ? '-' : '');

        return $sign.$formatted;
    }

    /**
     * @return array<int, TrendPoint>
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    public function hasPoints(): bool
    {
        return $this->points !== [];
    }

    /**
     * @return array<int, string>
     */
    public function getLabels(): array
    {
        return array_map(static fn (TrendPoint $point): string => $point->getLabel(), $this->points);
    }

    /**
     * @return array<int, float>
     */
    public function getValues(): array
    {
        return array_map(static fn (TrendPoint $point): float => $point->getValue(), $this->points);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getChartType(): string
    {
        return $this->type === 'area' ? 'line' : $this->type;
    }

    public function isFilled(): bool
    {
        return $this->type === 'area';
    }
}
