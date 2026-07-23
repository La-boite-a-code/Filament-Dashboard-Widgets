<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\CustomerDetailWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyDetailListWidget;
use Livewire\Livewire;

it('renders a description list of details', function (): void {
    Livewire::test(CustomerDetailWidget::class)
        ->assertSee('Customer')
        ->assertSee('Status')
        ->assertSee('Active')
        ->assertSee('Plan')
        ->assertSee('Pro')
        ->assertSee('€4,900.00')
        ->assertSeeHtml('href="https://example.test"')
        ->assertSeeHtml('<dl');
});

it('renders the empty state when there are no details', function (): void {
    Livewire::test(EmptyDetailListWidget::class)
        ->assertSee('No data');
});
