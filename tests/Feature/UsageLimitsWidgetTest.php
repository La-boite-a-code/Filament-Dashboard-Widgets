<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyUsageLimitsWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\ResourceUsageWidget;
use Livewire\Livewire;

it('renders usage rows with meters and a warning threshold', function (): void {
    Livewire::test(ResourceUsageWidget::class)
        ->assertSee('Plan usage')
        ->assertSee('Seats')
        ->assertSee('84%')
        ->assertSee('380 GB')
        ->assertSeeHtml('role="meter"')
        ->assertSeeHtml('width: 84%')
        ->assertSeeHtml('left: 80%');
});

it('renders the empty state when there are no limits', function (): void {
    Livewire::test(EmptyUsageLimitsWidget::class)
        ->assertSee('No data');
});
