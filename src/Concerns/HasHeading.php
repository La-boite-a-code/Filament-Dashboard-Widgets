<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Concerns;

trait HasHeading
{
    protected ?string $heading = null;

    protected ?string $headingDescription = null;

    public function heading(?string $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function headingDescription(?string $description): static
    {
        $this->headingDescription = $description;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function getHeadingDescription(): ?string
    {
        return $this->headingDescription;
    }

    public function hasHeading(): bool
    {
        return filled($this->getHeading()) || filled($this->getHeadingDescription());
    }
}
