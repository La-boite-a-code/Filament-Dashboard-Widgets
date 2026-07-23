<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasIcon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasUrl;

class VarianceItem
{
    use EvaluatesClosures;
    use HasColor;
    use HasIcon;
    use HasUrl;

    protected ?Closure $formatValueUsing = null;

    protected ?Closure $formatChangeUsing = null;

    protected float|int|Closure|null $previous = null;

    protected float|int|Closure|null $change = null;

    protected bool $lowerIsBetter = false;

    final public function __construct(
        protected string|Closure $label,
        protected float|int|Closure $value = 0,
    ) {}

    public static function make(string|Closure $label, float|int|Closure $value = 0): static
    {
        return new static($label, $value);
    }

    public function previous(float|int|Closure|null $previous): static
    {
        $this->previous = $previous;

        return $this;
    }

    public function change(float|int|Closure|null $change): static
    {
        $this->change = $change;

        return $this;
    }

    public function formatUsing(?Closure $callback): static
    {
        $this->formatValueUsing = $callback;

        return $this;
    }

    public function changeFormatUsing(?Closure $callback): static
    {
        $this->formatChangeUsing = $callback;

        return $this;
    }

    public function lowerIsBetter(bool $condition = true): static
    {
        $this->lowerIsBetter = $condition;

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

    public function getPrevious(): ?float
    {
        $previous = $this->evaluate($this->previous);

        return $previous === null ? null : (float) $previous;
    }

    public function getChange(): float
    {
        $change = $this->evaluate($this->change);

        if ($change !== null) {
            return (float) $change;
        }

        $previous = $this->getPrevious();

        return $previous === null ? 0.0 : $this->getValue() - $previous;
    }

    public function getPercentageChange(): ?float
    {
        $previous = $this->getPrevious();

        if ($previous === null || $previous === 0.0) {
            return null;
        }

        return ($this->getChange() / abs($previous)) * 100;
    }

    public function getDirection(): string
    {
        $change = $this->getChange();

        return match (true) {
            $change > 0.0 => 'up',
            $change < 0.0 => 'down',
            default => 'neutral',
        };
    }

    public function isPositive(): bool
    {
        return $this->getChange() > 0.0;
    }

    public function getChangeColor(): string
    {
        return match ($this->getDirection()) {
            'up' => $this->lowerIsBetter ? 'danger' : 'success',
            'down' => $this->lowerIsBetter ? 'success' : 'danger',
            default => 'gray',
        };
    }

    public function getChangeIcon(): string
    {
        return match ($this->getDirection()) {
            'up' => 'heroicon-m-arrow-up-right',
            'down' => 'heroicon-m-arrow-down-right',
            default => 'heroicon-m-minus-small',
        };
    }

    public function getFormattedValue(): string
    {
        if ($this->formatValueUsing !== null) {
            return (string) ($this->formatValueUsing)($this->getValue());
        }

        return (string) Number::format($this->getValue());
    }

    public function getFormattedChange(): string
    {
        $change = $this->getChange();

        if ($this->formatChangeUsing !== null) {
            return (string) ($this->formatChangeUsing)($change);
        }

        $sign = $change > 0.0 ? '+' : ($change < 0.0 ? '-' : '');

        return $sign.Number::format(abs($change));
    }

    public function getFormattedPercentageChange(): ?string
    {
        $percentage = $this->getPercentageChange();

        if ($percentage === null) {
            return null;
        }

        $sign = $percentage > 0.0 ? '+' : ($percentage < 0.0 ? '-' : '');

        return $sign.Number::percentage(abs($percentage), maxPrecision: 1);
    }

    public function getBarWidth(float $maxAbsoluteChange): float
    {
        if ($maxAbsoluteChange <= 0.0) {
            return 0.0;
        }

        return max(0.0, min(100.0, (abs($this->getChange()) / $maxAbsoluteChange) * 100));
    }
}
