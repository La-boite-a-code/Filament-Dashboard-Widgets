<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasDescription;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasIcon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasUrl;

class RecentItem
{
    use EvaluatesClosures;
    use HasColor;
    use HasDescription;
    use HasIcon;
    use HasUrl;

    protected string|Closure|null $meta = null;

    protected string|Closure|null $avatar = null;

    protected string|Closure|null $badge = null;

    protected string|Closure|null $badgeColor = null;

    final public function __construct(
        protected string|Closure $title,
        string|Closure|null $description = null,
    ) {
        $this->description = $description;
    }

    public static function make(string|Closure $title, string|Closure|null $description = null): static
    {
        return new static($title, $description);
    }

    public function meta(string|Closure|null $meta): static
    {
        $this->meta = $meta;

        return $this;
    }

    public function avatar(string|Closure|null $avatar): static
    {
        $this->avatar = $avatar;

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

    public function getTitle(): string
    {
        return (string) $this->evaluate($this->title);
    }

    public function getMeta(): ?string
    {
        $meta = $this->evaluate($this->meta);

        return $meta === null ? null : (string) $meta;
    }

    public function hasMeta(): bool
    {
        return filled($this->getMeta());
    }

    public function getAvatar(): ?string
    {
        $avatar = $this->evaluate($this->avatar);

        return filled($avatar) ? (string) $avatar : null;
    }

    public function hasAvatar(): bool
    {
        return $this->getAvatar() !== null;
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
}
