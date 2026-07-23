<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\Livewire\Partials\DataStoreOverride;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use LaBoiteACode\FilamentDashboardWidgets\FilamentDashboardWidgetsServiceProvider;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\TestPanelProvider;
use Livewire\LivewireServiceProvider;
use Livewire\Mechanisms\DataStore;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Livewire registers its data store as a shared instance, but Filament
        // rebinds it as a transient binding. Under Testbench that means the
        // per component store is never persisted between calls, which breaks
        // widget rendering. Restoring the shared binding fixes it for tests.
        $this->app->singleton(DataStore::class, DataStoreOverride::class);

        Filament::setCurrentPanel('admin');

        // Widgets rendered directly through Livewire::test() do not go through
        // the panel middleware that seeds the shared error bag, so provide one.
        $this->app['view']->share('errors', (new ViewErrorBag)->put('default', new MessageBag));
    }

    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentDashboardWidgetsServiceProvider::class,
            TestPanelProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }
}
