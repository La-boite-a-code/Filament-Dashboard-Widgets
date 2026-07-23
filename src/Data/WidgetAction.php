<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasIcon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasUrl;

class WidgetAction
{
    use EvaluatesClosures;
    use HasColor;
    use HasIcon;
    use HasUrl;

    final public function __construct(
        protected string|Closure $label,
    ) {}

    public static function make(string|Closure $label): static
    {
        return new static($label);
    }

    public function getLabel(): string
    {
        return (string) $this->evaluate($this->label);
    }
}
