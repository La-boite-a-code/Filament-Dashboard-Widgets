<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
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
         * The stylesheet is registered as a Filament asset so that
         * "php artisan filament:assets" publishes it and browsers cache it. It
         * is marked as loaded on request because Filament would otherwise link
         * it on every page, even in apps that never published it, which would
         * mean a 404 per page load. The render hook links the published file
         * when it exists and falls back to inlining the stylesheet otherwise,
         * so the package keeps working out of the box without any build step.
         */
        $stylesheet = Css::make(static::$name, $this->getStylesheetPath())->loadedOnRequest();

        FilamentAsset::register([$stylesheet], package: 'laboiteacode/filament-dashboard-widgets');

        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn (): Htmlable => $this->renderStylesheet($stylesheet),
        );
    }

    protected function renderStylesheet(Css $stylesheet): Htmlable
    {
        if (is_file($stylesheet->getPublicPath())) {
            return $stylesheet->getHtml();
        }

        return new HtmlString('<style>'.$this->getStylesheet().'</style>');
    }

    protected function getStylesheetPath(): string
    {
        return __DIR__.'/../resources/dist/filament-dashboard-widgets.css';
    }

    protected function getStylesheet(): string
    {
        static $stylesheet = null;

        if ($stylesheet === null) {
            $path = $this->getStylesheetPath();
            $stylesheet = is_file($path) ? (string) file_get_contents($path) : '';
        }

        return $stylesheet;
    }
}
