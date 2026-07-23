<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use LaBoiteACode\FilamentDashboardWidgets\FilamentDashboardWidgetsPlugin;

class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->plugin(FilamentDashboardWidgetsPlugin::make());
    }
}
