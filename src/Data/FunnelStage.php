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

class FunnelStage
{
    use EvaluatesClosures;
    use HasColor;
    use HasDescription;
    use HasIcon;
    use HasUrl;

    protected ?Closure $formatValueUsing = null;

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

        return (string) Number::format($this->getValue());
    }

    /**
     * Width relative to the first (widest) stage, which gives the funnel its
     * tapering shape.
     */
    public function getBarWidth(float $topValue): float
    {
        if ($topValue <= 0.0) {
            return 0.0;
        }

        return max(0.0, min(100.0, ($this->getValue() / $topValue) * 100));
    }
}
