<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Data;

use Closure;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\EvaluatesClosures;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasColor;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasDescription;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasIcon;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasUrl;

class TimelineEvent
{
    use EvaluatesClosures;
    use HasColor;
    use HasDescription;
    use HasIcon;
    use HasUrl;

    protected DateTimeInterface|string|Closure|null $timestamp = null;

    protected string|Closure|null $actor = null;

    protected string|Closure|null $avatar = null;

    protected string|Closure|null $badge = null;

    protected string|Closure|null $badgeColor = null;

    final public function __construct(
        protected string|Closure $title,
    ) {}

    public static function make(string|Closure $title): static
    {
        return new static($title);
    }

    public function timestamp(DateTimeInterface|string|Closure|null $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function actor(string|Closure|null $actor): static
    {
        $this->actor = $actor;

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

    public function getTimestamp(): ?Carbon
    {
        $timestamp = $this->evaluate($this->timestamp);

        if ($timestamp === null) {
            return null;
        }

        if ($timestamp instanceof DateTimeInterface) {
            return Carbon::instance($timestamp);
        }

        return Carbon::parse((string) $timestamp);
    }

    public function hasTimestamp(): bool
    {
        return $this->getTimestamp() !== null;
    }

    public function getRelativeTime(): ?string
    {
        return $this->getTimestamp()?->diffForHumans();
    }

    public function getDateTimeString(): ?string
    {
        return $this->getTimestamp()?->toIso8601String();
    }

    public function getActor(): ?string
    {
        $actor = $this->evaluate($this->actor);

        return $actor === null ? null : (string) $actor;
    }

    public function hasActor(): bool
    {
        return filled($this->getActor());
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
        return $this->sanitizeColorName($this->evaluate($this->badgeColor));
    }
}
