<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\CustomerBreakdownWidget;
use Livewire\Livewire;

it('renders a breakdown with computed percentages and bars', function (): void {
    Livewire::test(CustomerBreakdownWidget::class)
        ->assertSee('Customers by status')
        ->assertSee('Active')
        ->assertSee('Pending')
        ->assertSee('148')
        ->assertSee('74%')
        ->assertSeeHtml('fi-fdw-bar__fill');
});
