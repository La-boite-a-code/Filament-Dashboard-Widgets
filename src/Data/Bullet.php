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

class Bullet
{
    use EvaluatesClosures;
    use HasColor;
    use HasDescription;
    use HasIcon;
    use HasUrl;

    protected ?Closure $formatValueUsing = null;

    protected float|int|Closure|null $target = null;

    protected float|int|Closure|null $max = null;

    protected float|int|Closure|null $comparative = null;

    /** @var array<int, float|int> */
    protected array $ranges = [];

    protected bool $lowerIsBetter = false;

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

    public function target(float|int|Closure|null $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function max(float|int|Closure|null $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function comparative(float|int|Closure|null $comparative): static
    {
        $this->comparative = $comparative;

        return $this;
    }

    /**
     * Ascending thresholds that split the track into neutral qualitative bands
     * (for example [50, 80] renders poor / fair / good context zones).
     *
     * @param  array<int, float|int>  $ranges
     */
    public function ranges(array $ranges): static
    {
        $this->ranges = array_values($ranges);

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

    public function getTarget(): ?float
    {
        $target = $this->evaluate($this->target);

        return $target === null ? null : (float) $target;
    }

    public function getComparative(): ?float
    {
        $comparative = $this->evaluate($this->comparative);

        return $comparative === null ? null : (float) $comparative;
    }

    /**
     * @return array<int, float>
     */
    public function getRanges(): array
    {
        $ranges = array_map(static fn (float|int $value): float => (float) $value, $this->ranges);
        sort($ranges);

        return $ranges;
    }

    public function getScaleMax(): float
    {
        $max = $this->evaluate($this->max);

        if ($max !== null) {
            return (float) $max;
        }

        $candidates = [$this->getValue()];

        if (($target = $this->getTarget()) !== null) {
            $candidates[] = $target;
        }

        if (($comparative = $this->getComparative()) !== null) {
            $candidates[] = $comparative;
        }

        $ranges = $this->getRanges();

        if ($ranges !== []) {
            $candidates[] = max($ranges);
        }

        return max($candidates);
    }

    protected function toPercentage(?float $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $scale = $this->getScaleMax();

        if ($scale <= 0.0) {
            return 0.0;
        }

        return max(0.0, min(100.0, ($value / $scale) * 100));
    }

    public function getValuePercentage(): float
    {
        return $this->toPercentage($this->getValue()) ?? 0.0;
    }

    public function getTargetPercentage(): ?float
    {
        return $this->toPercentage($this->getTarget());
    }

    public function getComparativePercentage(): ?float
    {
        return $this->toPercentage($this->getComparative());
    }

    /**
     * @return array<int, array{width: float, shade: int}>
     */
    public function getBands(): array
    {
        $scale = $this->getScaleMax();

        if ($scale <= 0.0) {
            return [];
        }

        $bounds = [];

        foreach ($this->getRanges() as $range) {
            if ($range > 0.0 && $range < $scale) {
                $bounds[] = $range;
            }
        }

        $bounds = array_values(array_unique($bounds));

        if ($bounds === []) {
            return [];
        }

        $bands = [];
        $previous = 0.0;
        $shade = 1;

        foreach ($bounds as $bound) {
            $bands[] = ['width' => round((($bound - $previous) / $scale) * 100, 2), 'shade' => min($shade, 4)];
            $previous = $bound;
            $shade++;
        }

        $bands[] = ['width' => round((($scale - $previous) / $scale) * 100, 2), 'shade' => min($shade, 4)];

        return $bands;
    }

    public function getStatus(): string
    {
        $target = $this->getTarget();

        if ($target === null) {
            return 'none';
        }

        $value = $this->getValue();

        return match (true) {
            $value > $target => 'above',
            $value < $target => 'below',
            default => 'met',
        };
    }

    public function isReached(): bool
    {
        $target = $this->getTarget();

        if ($target === null) {
            return false;
        }

        return $this->lowerIsBetter
            ? $this->getValue() <= $target
            : $this->getValue() >= $target;
    }

    public function getStatusColor(): string
    {
        if ($this->getTarget() === null) {
            return 'gray';
        }

        return $this->isReached() ? 'success' : 'warning';
    }

    protected function format(float $value): string
    {
        if ($this->formatValueUsing !== null) {
            return (string) ($this->formatValueUsing)($value);
        }

        return (string) Number::format($value);
    }

    public function getFormattedValue(): string
    {
        return $this->format($this->getValue());
    }

    public function getFormattedTarget(): ?string
    {
        $target = $this->getTarget();

        return $target === null ? null : $this->format($target);
    }

    public function getFormattedComparative(): ?string
    {
        $comparative = $this->getComparative();

        return $comparative === null ? null : $this->format($comparative);
    }
}
