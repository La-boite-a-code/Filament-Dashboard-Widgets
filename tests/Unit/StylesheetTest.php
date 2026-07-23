<?php

declare(strict_types=1);

it('ships a self contained, theme aware stylesheet', function (): void {
    $css = (string) file_get_contents(__DIR__.'/../../resources/dist/filament-dashboard-widgets.css');

    expect($css)
        ->toContain('.fi-fdw-card')
        ->toContain('.dark .fi-fdw-card')
        ->toContain('prefers-reduced-motion')
        ->toContain('var(--gray-')
        ->not->toContain('#0ea5e9'); // no hard coded accent colour
});
