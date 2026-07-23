<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentDashboardWidgetsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-dashboard-widgets';
    }

    public function register(Panel $panel): void
    {
        /*
         * Every widget shipped by this package is an abstract base class that
         * the application extends with its own data. There is therefore nothing
         * to auto-register on the panel: applications add their concrete widgets
         * through the usual "widgets()", "getHeaderWidgets()" or
         * "getFooterWidgets()" methods. The plugin still exists so the package
         * follows the documented Filament registration flow and stays a natural
         * extension point for future options.
         */
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
