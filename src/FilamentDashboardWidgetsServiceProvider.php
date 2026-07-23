<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentDashboardWidgetsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-dashboard-widgets';

    public function configurePackage(Package $package): void
    {
        /*
         * The package ships a config file, Blade views and translations. All of
         * them are registered under the "filament-dashboard-widgets" namespace
         * and remain optional to publish thanks to the fallback defaults.
         */
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        /*
         * The widgets are styled with a small, self contained stylesheet built
         * on Filament design tokens. It is injected inline into the panel head
         * so the package works out of the box, without any asset publishing or
         * front-end build step.
         */
        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn (): HtmlString => new HtmlString('<style>'.$this->getStylesheet().'</style>'),
        );
    }

    protected function getStylesheet(): string
    {
        static $stylesheet = null;

        if ($stylesheet === null) {
            $path = __DIR__.'/../resources/dist/filament-dashboard-widgets.css';
            $stylesheet = is_file($path) ? (string) file_get_contents($path) : '';
        }

        return $stylesheet;
    }
}
