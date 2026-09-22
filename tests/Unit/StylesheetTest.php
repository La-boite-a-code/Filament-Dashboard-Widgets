<?php

declare(strict_types=1);

/**
 * Returns the declarations applying to a selector, merged in source order.
 * Rules nested in at-rules are merged as if they were global, so only use
 * it for selectors that are not scoped to a media query.
 *
 * @return array<string, string>
 */
function stylesheetDeclarations(string $selector): array
{
    $css = (string) file_get_contents(__DIR__.'/../../resources/dist/filament-dashboard-widgets.css');
    $css = (string) preg_replace('#/\*.*?\*/#s', '', $css);

    preg_match_all('/([^{}]+)\{([^{}]*)\}/', $css, $rules, PREG_SET_ORDER);

    $declarations = [];

    foreach ($rules as [, $selectors, $body]) {
        $list = array_map(
            static fn (string $item): string => (string) preg_replace('/\s+/', ' ', trim($item)),
            explode(',', $selectors),
        );

        if (! in_array($selector, $list, true)) {
            continue;
        }

        foreach (explode(';', $body) as $declaration) {
            if (! str_contains($declaration, ':')) {
                continue;
            }

            [$property, $value] = array_map('trim', explode(':', $declaration, 2));
            $declarations[$property] = (string) preg_replace('/\s+/', ' ', $value);
        }
    }

    return $declarations;
}

function stylesheet(): string
{
    return (string) file_get_contents(__DIR__.'/../../resources/dist/filament-dashboard-widgets.css');
}

it('ships a self contained, theme aware stylesheet', function (): void {
    expect(stylesheet())
        ->toContain('.fi-fdw-card')
        ->toContain('.dark .fi-fdw-card')
        ->toContain('prefers-reduced-motion')
        ->toContain('var(--gray-')
        ->not->toContain('#0ea5e9'); // no hard coded accent colour
});

it('draws the card surface exactly like a native Filament widget', function (): void {
    $card = stylesheetDeclarations('.fi-fdw-card');

    expect($card)
        ->toHaveKey('background-color', 'var(--color-white)')
        ->toHaveKey('border-radius', '0.75rem')
        ->toHaveKey('padding', '1.5rem')
        ->not->toHaveKey('border')
        ->and($card['box-shadow'])
        ->toContain('0 0 0 var(--fi-fdw-ring-width) var(--fi-fdw-ring)')
        ->toContain('0 1px 3px 0 rgb(0 0 0 / 0.1)')
        ->toContain('0 1px 2px -1px rgb(0 0 0 / 0.1)')
        ->and($card['--fi-fdw-ring'])->toBe('color-mix(in oklab, var(--gray-950) 5%, transparent)')
        ->and($card['--fi-fdw-ring-width'])->toBe('1px');

    expect(stylesheetDeclarations('.dark .fi-fdw-card'))
        ->toHaveKey('background-color', 'var(--gray-900)')
        ->toHaveKey('--fi-fdw-ring', 'color-mix(in oklab, var(--color-white) 10%, transparent)');
});

it('keeps clickable cards still on hover and rings them in primary on focus', function (): void {
    expect(stylesheetDeclarations('.fi-fdw-card--interactive:hover'))->toBe([])
        ->and(stylesheetDeclarations('.fi-fdw-card--interactive'))
        ->not->toHaveKey('transform')
        ->not->toHaveKey('transition')
        ->and(stylesheetDeclarations('.fi-fdw-card--interactive:focus-visible'))
        ->toHaveKey('--fi-fdw-ring', 'var(--primary-600)')
        ->toHaveKey('--fi-fdw-ring-width', '2px');
});

it('uses the native section heading scale', function (): void {
    expect(stylesheetDeclarations('.fi-fdw-heading__title'))
        ->toHaveKey('font-size', '1rem')
        ->toHaveKey('line-height', '1.5rem')
        ->toHaveKey('font-weight', '600');

    expect(stylesheetDeclarations('.fi-fdw-heading__description'))
        ->toHaveKey('font-size', '0.875rem')
        ->toHaveKey('line-height', '1.25rem');
});

it('renders headline values like native stats', function (): void {
    expect(stylesheet())
        ->not->toContain('font-weight: 700')
        ->not->toContain('font-size: 2.25rem');

    expect(stylesheetDeclarations('.fi-fdw-metric__value'))
        ->toHaveKey('font-size', '1.875rem')
        ->toHaveKey('font-weight', '600');
});

it('tints icon wells like native Filament icon backgrounds', function (string $selector): void {
    // Views published before 1.0.1 only expose the 400 to 600 shades, so the
    // 100 shade falls back to the 1.0.0 tint instead of going transparent.
    expect(stylesheetDeclarations($selector))
        ->toHaveKey('color', 'var(--color-500)')
        ->toHaveKey('background-color', 'var(--color-100, color-mix(in srgb, var(--color-500) 12%, transparent))');

    expect(stylesheetDeclarations('.dark '.$selector))
        ->toHaveKey('color', 'var(--color-400)')
        ->toHaveKey('background-color', 'color-mix(in oklab, var(--color-500) 20%, transparent)');
})->with([
    '.fi-fdw-metric__icon',
    '.fi-fdw-recent__visual',
    '.fi-fdw-timeline__marker',
    '.fi-fdw-cardw__stat-icon',
    '.fi-fdw-cardw__tile',
]);

it('separates rows like native Filament tables', function (): void {
    expect(stylesheetDeclarations('.fi-fdw-details__row'))
        ->toHaveKey('border-bottom', '1px solid var(--gray-200)');

    expect(stylesheetDeclarations('.dark .fi-fdw-details__row'))
        ->toHaveKey('border-color', 'color-mix(in oklab, var(--color-white) 5%, transparent)');

    expect(stylesheetDeclarations('.dark .fi-fdw-timeline__event:not(:last-child)::before'))
        ->toHaveKey('background-color', 'color-mix(in oklab, var(--color-white) 10%, transparent)')
        ->and(stylesheetDeclarations('.dark .fi-fdw-funnel__connector-line'))
        ->toHaveKey('background-color', 'color-mix(in oklab, var(--color-white) 10%, transparent)');
});

it('draws every focus indicator in the native primary shade', function (): void {
    expect(stylesheet())->not->toContain('var(--primary-500)');
});

it('sizes widget descriptions like native stat and section descriptions', function (): void {
    expect(stylesheetDeclarations('.fi-fdw-description'))
        ->toHaveKey('font-size', '0.875rem')
        ->toHaveKey('line-height', '1.25rem')
        ->toHaveKey('color', 'var(--gray-500)');

    expect(stylesheetDeclarations('.dark .fi-fdw-description'))
        ->toHaveKey('color', 'var(--gray-400)');
});
