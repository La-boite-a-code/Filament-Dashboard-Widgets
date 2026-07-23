<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyTrendWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\NewCustomersTrendWidget;
use Livewire\Livewire;

it('renders a trend chart with a summary heading and comparison', function (): void {
    Livewire::test(NewCustomersTrendWidget::class)
        ->assertSee('New customers')
        ->assertSee('184')
        ->assertSeeHtml('canvas');
});

it('renders the native empty state when the trend has no points', function (): void {
    Livewire::test(EmptyTrendWidget::class)
        ->assertSee('No data');
});
