<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyMetricWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\HostileColorMetricWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\RevenueMetricWidget;
use Livewire\Livewire;

it('renders a metric with value, label, trend and sparkline', function (): void {
    Livewire::test(RevenueMetricWidget::class)
        ->assertSee('Monthly revenue')
        ->assertSee('€24,850.00')
        ->assertSee('Compared to last month')
        ->assertSeeHtml('fi-fdw-card')
        ->assertSeeHtml('fi-fdw-metric__sparkline-line')
        ->assertSeeHtml('href="https://example.test/orders"');
});

it('renders the empty state when the metric has no value', function (): void {
    Livewire::test(EmptyMetricWidget::class)
        ->assertSee('No data')
        ->assertDontSeeHtml('fi-fdw-metric__value');
});

it('never renders injected CSS declarations coming from a colour setter', function (): void {
    $html = Livewire::test(HostileColorMetricWidget::class)->html();

    preg_match_all('/style="([^"]*)"/', $html, $matches);

    $colorStyles = array_values(array_filter(
        $matches[1],
        static fn (string $style): bool => str_contains($style, '--color-'),
    ));

    expect($colorStyles)->not->toBeEmpty()
        ->and($html)->not->toContain('url(');

    foreach ($colorStyles as $style) {
        expect($style)->toMatch('/^(?:--color-\d+:var\(--[a-zA-Z0-9_-]+-\d+\);?)+$/');
    }
});
