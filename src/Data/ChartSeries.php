<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use InvalidArgumentException;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;

class ChartSeries
{
    use EvaluatesClosures;
    use HasColor;

    public const array TYPES = ['line', 'bar'];

    protected string $type = 'line';

    protected bool $filled = false;

    /** @var array<int, float|int> */
    protected array $values = [];

    final public function __construct(
        protected string|Closure $label,
    ) {}

    public static function make(string|Closure $label): static
    {
        return new static($label);
    }

    /**
     * @param  array<int, float|int>  $values
     */
    public function values(array $values): static
    {
        $this->values = array_values($values);

        return $this;
    }

    public function type(string $type): static
    {
        if (! in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException(
                "Unsupported series type [{$type}]. Supported types are: ".implode(', ', self::TYPES).'.'
            );
        }

        $this->type = $type;

        return $this;
    }

    public function filled(bool $condition = true): static
    {
        $this->filled = $condition;

        return $this;
    }

    public function getLabel(): string
    {
        return (string) $this->evaluate($this->label);
    }

    /**
     * @return array<int, float>
     */
    public function getValues(): array
    {
        return array_map(static fn (float|int $value): float => (float) $value, $this->values);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isFilled(): bool
    {
        return $this->filled;
    }
}
