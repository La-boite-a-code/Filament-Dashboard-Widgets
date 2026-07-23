<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;

class CompositionSlice
{
    use EvaluatesClosures;
    use HasColor;

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
}
