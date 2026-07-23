<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyCompositionWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\RevenueCompositionWidget;
use Livewire\Livewire;

it('renders a doughnut composition with a total and resolved slice colours', function (): void {
    Livewire::test(RevenueCompositionWidget::class)
        ->assertSee('Revenue by channel')
        ->assertSee('€25,000.00')
        ->assertSeeHtml('data-chart-type="doughnut"')
        ->assertSeeHtml('oklch');
});

it('renders the native empty state when the composition has no slices', function (): void {
    Livewire::test(EmptyCompositionWidget::class)
        ->assertSee('No data');
});
