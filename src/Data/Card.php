<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use Illuminate\Support\Number;
use InvalidArgumentException;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasIcon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasUrl;

class Card
{
    use EvaluatesClosures;
    use HasColor;
    use HasIcon;
    use HasUrl;

    public const array VARIANTS = ['stat', 'icon', 'content', 'cta'];

    protected string $variant = 'content';

    protected string|Closure|null $description = null;

    protected mixed $value = null;

    protected ?Closure $formatValueUsing = null;

    protected string|Closure|null $badge = null;

    protected string|Closure|null $badgeColor = null;

    /** @var array<int, WidgetAction> */
    protected array $actions = [];

    final public function __construct(
        protected string|Closure $heading,
    ) {}

    public static function make(string|Closure $heading): static
    {
        return new static($heading);
    }

    public function variant(string $variant): static
    {
        if (! in_array($variant, self::VARIANTS, true)) {
            throw new InvalidArgumentException(
                "Unsupported card variant [{$variant}]. Supported variants are: ".implode(', ', self::VARIANTS).'.'
            );
        }

        $this->variant = $variant;

        return $this;
    }

    public function description(string|Closure|null $description): static
    {
        $this->description = $description;

        return $this;
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

    public function badge(string|Closure|null $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    public function badgeColor(string|Closure|null $color): static
    {
        $this->badgeColor = $color;

        return $this;
    }

    /**
     * @param  array<int, WidgetAction>  $actions
     */
    public function actions(array $actions): static
    {
        foreach ($actions as $action) {
            if (! $action instanceof WidgetAction) {
                throw new InvalidArgumentException('Card actions must be instances of '.WidgetAction::class.'.');
            }
        }

        $this->actions = array_values($actions);

        return $this;
    }

    public function getHeading(): string
    {
        return (string) $this->evaluate($this->heading);
    }

    public function getVariant(): string
    {
        return $this->variant;
    }

    public function getDescription(): ?string
    {
        $description = $this->evaluate($this->description);

        return $description === null ? null : (string) $description;
    }

    public function hasDescription(): bool
    {
        return filled($this->getDescription());
    }

    public function getValue(): mixed
    {
        return $this->evaluate($this->value);
    }

    public function hasValue(): bool
    {
        $value = $this->getValue();

        return $value !== null && $value !== '';
    }

    public function getFormattedValue(): ?string
    {
        if (! $this->hasValue()) {
            return null;
        }

        $value = $this->getValue();

        if ($this->formatValueUsing !== null) {
            return (string) ($this->formatValueUsing)($value);
        }

        if (is_int($value) || is_float($value)) {
            return (string) Number::format($value);
        }

        return (string) $value;
    }

    public function getBadge(): ?string
    {
        $badge = $this->evaluate($this->badge);

        return $badge === null ? null : (string) $badge;
    }

    public function hasBadge(): bool
    {
        return filled($this->getBadge());
    }

    public function getBadgeColor(): ?string
    {
        $color = $this->evaluate($this->badgeColor);

        return $color === null ? null : (string) $color;
    }

    /**
     * @return array<int, WidgetAction>
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function hasActions(): bool
    {
        return $this->actions !== [];
    }
}
