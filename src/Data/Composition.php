<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use Illuminate\Support\Number;
use InvalidArgumentException;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;

class Composition
{
    use EvaluatesClosures;

    public const array TYPES = ['doughnut', 'pie', 'polarArea'];

    protected ?Closure $formatValueUsing = null;

    protected float|int|Closure|null $total = null;

    /** @var array<int, CompositionSlice> */
    protected array $slices = [];

    protected string $type = 'doughnut';

    final public function __construct(
        protected string|Closure $label,
    ) {}

    public static function make(string|Closure $label): static
    {
        return new static($label);
    }

    /**
     * @param  array<int, CompositionSlice>  $slices
     */
    public function slices(array $slices): static
    {
        foreach ($slices as $slice) {
            if (! $slice instanceof CompositionSlice) {
                throw new InvalidArgumentException(
                    'Composition slices must be instances of '.CompositionSlice::class.'.'
                );
            }
        }

        $this->slices = array_values($slices);

        return $this;
    }

    public function type(string $type): static
    {
        if (! in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException(
                "Unsupported composition type [{$type}]. Supported types are: ".implode(', ', self::TYPES).'.'
            );
        }

        $this->type = $type;

        return $this;
    }

    public function formatUsing(?Closure $callback): static
    {
        $this->formatValueUsing = $callback;

        return $this;
    }

    public function total(float|int|Closure|null $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getLabel(): string
    {
        return (string) $this->evaluate($this->label);
    }

    /**
     * @return array<int, CompositionSlice>
     */
    public function getSlices(): array
    {
        return $this->slices;
    }

    public function hasSlices(): bool
    {
        return $this->slices !== [];
    }

    /**
     * @return array<int, string>
     */
    public function getLabels(): array
    {
        return array_map(static fn (CompositionSlice $slice): string => $slice->getLabel(), $this->slices);
    }

    /**
     * @return array<int, float>
     */
    public function getValues(): array
    {
        return array_map(static fn (CompositionSlice $slice): float => $slice->getValue(), $this->slices);
    }

    /**
     * @return array<int, string|null>
     */
    public function getColorTokens(): array
    {
        return array_map(static fn (CompositionSlice $slice): ?string => $slice->getColor(), $this->slices);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTotal(): float
    {
        $total = $this->evaluate($this->total);

        if ($total !== null) {
            return (float) $total;
        }

        return array_sum($this->getValues());
    }

    public function getFormattedTotal(): string
    {
        $total = $this->getTotal();

        if ($this->formatValueUsing !== null) {
            return (string) ($this->formatValueUsing)($total);
        }

        return (string) Number::format($total, locale: app()->getLocale());
    }
}
