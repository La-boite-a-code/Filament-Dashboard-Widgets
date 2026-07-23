<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\FunnelStage;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\FunnelWidget;

class SignupFunnelWidget extends FunnelWidget
{
    protected ?string $heading = 'Signup funnel';

    protected function getStages(): array
    {
        return [
            FunnelStage::make('Visitors', 4_200),
            FunnelStage::make('Signups', 1_280),
            FunnelStage::make('Activated', 640),
            FunnelStage::make('Paying', 210)->color('success'),
        ];
    }
}
