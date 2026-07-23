<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;

class TrendPoint
{
    use EvaluatesClosures;

    final public function __construct(
        protected string|Closure $label,
        protected float|int|Closure $value = 0,
    ) {}

    public static function make(string|Closure $label, float|int|Closure $value = 0): static
    {
        return new static($label, $value);
    }

    public function getLabel(): string
    {
        return (string) $this->evaluate($this->label);
    }

    public function getValue(): float
    {
        return (float) $this->evaluate($this->value);
    }
}
