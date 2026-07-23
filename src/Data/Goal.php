<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasDescription;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasIcon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasUrl;

class Goal
{
    use EvaluatesClosures;
    use HasColor;
    use HasDescription;
    use HasIcon;
    use HasUrl;

    protected ?Closure $formatValueUsing = null;

    protected DateTimeInterface|string|Closure|null $deadline = null;

    protected ?bool $showRemaining = null;

    protected ?bool $showPercentage = null;

    protected bool $allowOverflow = false;

    final public function __construct(
        protected string|Closure $label,
        protected float|int|Closure $current = 0,
        protected float|int|Closure $target = 0,
    ) {}

    public static function make(
        string|Closure $label,
        float|int|Closure $current = 0,
        float|int|Closure $target = 0,
    ): static {
        return new static($label, $current, $target);
    }

    public function formatUsing(?Closure $callback): static
    {
        $this->formatValueUsing = $callback;

        return $this;
    }

    public function deadline(DateTimeInterface|string|Closure|null $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function showRemaining(bool $condition = true): static
    {
        $this->showRemaining = $condition;

        return $this;
    }

    public function showPercentage(bool $condition = true): static
    {
        $this->showPercentage = $condition;

        return $this;
    }

    public function allowOverflow(bool $condition = true): static
    {
        $this->allowOverflow = $condition;

        return $this;
    }

    public function getLabel(): string
    {
        return (string) $this->evaluate($this->label);
    }

    public function getCurrent(): float
    {
        return (float) $this->evaluate($this->current);
    }

    public function getTarget(): float
    {
        return (float) $this->evaluate($this->target);
    }

    /**
     * Real progress percentage. Guards against division by zero and returns a
     * defined value when the target is zero or negative.
     */
    public function getPercentage(): float
    {
        $target = $this->getTarget();
        $current = $this->getCurrent();

        if ($target <= 0.0) {
            return $current > 0.0 ? 100.0 : 0.0;
        }

        return ($current / $target) * 100;
    }

    /**
     * Width of the progress bar, always clamped to the 0..100 range because a
     * bar cannot physically overflow its track.
     */
    public function getBarWidth(): float
    {
        return max(0.0, min(100.0, $this->getPercentage()));
    }

    /**
     * Percentage shown as text. Capped at 100 by default, uncapped when
     * overflow is allowed or the global cap is disabled.
     */
    public function getDisplayPercentage(): float
    {
        $percentage = $this->getPercentage();

        if ($this->shouldCapPercentage()) {
            return max(0.0, min(100.0, $percentage));
        }

        return $percentage;
    }

    public function getRemaining(): float
    {
        return max(0.0, $this->getTarget() - $this->getCurrent());
    }

    public function getOverflow(): float
    {
        return max(0.0, $this->getCurrent() - $this->getTarget());
    }

    public function isReached(): bool
    {
        return $this->getTarget() > 0.0 && $this->getCurrent() >= $this->getTarget();
    }

    public function isExceeded(): bool
    {
        return $this->getTarget() > 0.0 && $this->getCurrent() > $this->getTarget();
    }

    public function getStatus(): string
    {
        return match (true) {
            $this->isExceeded() => 'exceeded',
            $this->isReached() => 'reached',
            $this->isOverdue() => 'overdue',
            default => 'in_progress',
        };
    }

    public function getDeadline(): ?Carbon
    {
        $deadline = $this->evaluate($this->deadline);

        if ($deadline === null) {
            return null;
        }

        if ($deadline instanceof DateTimeInterface) {
            return Carbon::instance($deadline);
        }

        return Carbon::parse((string) $deadline);
    }

    public function hasDeadline(): bool
    {
        return $this->getDeadline() !== null;
    }

    public function isOverdue(): bool
    {
        $deadline = $this->getDeadline();

        if ($deadline === null) {
            return false;
        }

        return $deadline < now() && ! $this->isReached();
    }

    public function shouldShowRemaining(): bool
    {
        return $this->showRemaining ?? (bool) config('filament-dashboard-widgets.goal.show_remaining', true);
    }

    public function shouldShowPercentage(): bool
    {
        return $this->showPercentage ?? (bool) config('filament-dashboard-widgets.goal.show_percentage', true);
    }

    public function allowsOverflow(): bool
    {
        return $this->allowOverflow;
    }

    protected function shouldCapPercentage(): bool
    {
        if ($this->allowOverflow) {
            return false;
        }

        return (bool) config('filament-dashboard-widgets.goal.cap_progress_at_100', true);
    }

    protected function format(float $value): string
    {
        if ($this->formatValueUsing !== null) {
            return (string) ($this->formatValueUsing)($value);
        }

        return (string) Number::format($value);
    }

    public function getFormattedCurrent(): string
    {
        return $this->format($this->getCurrent());
    }

    public function getFormattedTarget(): string
    {
        return $this->format($this->getTarget());
    }

    public function getFormattedRemaining(): string
    {
        return $this->format($this->getRemaining());
    }

    public function getFormattedOverflow(): string
    {
        return $this->format($this->getOverflow());
    }

    public function getFormattedPercentage(): string
    {
        return (string) Number::percentage($this->getDisplayPercentage(), maxPrecision: 1);
    }
}
