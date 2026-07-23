<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyRecentItemsWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\RecentOrdersWidget;
use Livewire\Livewire;

it('renders recent items with badges and a Filament view all action', function (): void {
    Livewire::test(RecentOrdersWidget::class)
        ->assertSee('Recent orders')
        ->assertSee('#1001')
        ->assertSee('Ada Lovelace')
        ->assertSee('Paid')
        ->assertSee('View all')
        ->assertSeeHtml('href="https://example.test/orders"')
        ->assertSeeHtml('href="https://example.test/orders/1001"');
});

it('renders the empty state when there are no items', function (): void {
    Livewire::test(EmptyRecentItemsWidget::class)
        ->assertSee('No data')
        ->assertDontSee('View all');
});
