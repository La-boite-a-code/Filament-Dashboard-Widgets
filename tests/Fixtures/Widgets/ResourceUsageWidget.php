<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\UsageLimit;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\UsageLimitsWidget;

class ResourceUsageWidget extends UsageLimitsWidget
{
    protected ?string $heading = 'Plan usage';

    protected function getLimits(): array
    {
        return [
            UsageLimit::make('Seats', 42, 50)->warnThreshold(0.8)->icon('heroicon-o-users'),
            UsageLimit::make('Storage', 380, 500)
                ->warnThreshold(0.9)
                ->formatUsing(fn (float $value): string => number_format($value).' GB'),
            UsageLimit::make('API calls', 92_000, 100_000)->warnThreshold(0.8),
        ];
    }
}
