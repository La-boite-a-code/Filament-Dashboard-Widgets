<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyMetricWidget;
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
