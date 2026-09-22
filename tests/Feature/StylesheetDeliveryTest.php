<?php

declare(strict_types=1);

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\File;

function dashboardWidgetsStylesheetAsset(): ?Css
{
    foreach (FilamentAsset::getStyles(['laboiteacode/filament-dashboard-widgets']) as $asset) {
        if ($asset->getId() === 'filament-dashboard-widgets') {
            return $asset;
        }
    }

    return null;
}

function renderStylesHook(): string
{
    return FilamentView::renderHook(PanelsRenderHook::STYLES_AFTER)->toHtml();
}

afterEach(function (): void {
    File::deleteDirectory(public_path('css/laboiteacode'));

    if (File::isDirectory(public_path('css')) && File::isEmptyDirectory(public_path('css'))) {
        File::deleteDirectory(public_path('css'));
    }
});

it('registers the stylesheet as an asset that filament:assets can publish', function (): void {
    $asset = dashboardWidgetsStylesheetAsset();

    expect($asset)->not->toBeNull()
        ->and(realpath((string) $asset->getPath()))
        ->toBe(realpath(__DIR__.'/../../resources/dist/filament-dashboard-widgets.css'))
        ->and($asset->isLoadedOnRequest())->toBeTrue();
});

it('injects the stylesheet inline while the asset has not been published', function (): void {
    expect(renderStylesHook())
        ->toContain('<style>')
        ->toContain('.fi-fdw-card')
        ->not->toContain('<link');
});

it('links the published stylesheet instead of inlining it', function (): void {
    $asset = dashboardWidgetsStylesheetAsset();

    expect($asset)->not->toBeNull();

    File::ensureDirectoryExists(dirname($asset->getPublicPath()));
    File::copy((string) $asset->getPath(), $asset->getPublicPath());

    expect(renderStylesHook())
        ->toContain('<link')
        ->toContain($asset->getRelativePublicPath())
        ->not->toContain('<style>')
        ->not->toContain('.fi-fdw-card');
});
