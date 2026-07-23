<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyBulletWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\SatisfactionBulletWidget;
use Livewire\Livewire;

it('renders a bullet graph with bands, a target tick and a status', function (): void {
    Livewire::test(SatisfactionBulletWidget::class)
        ->assertSee('Customer satisfaction')
        ->assertSee('82%')
        ->assertSee('Below target')
        ->assertSeeHtml('role="meter"')
        ->assertSeeHtml('fi-fdw-bullet__band--3')
        ->assertSeeHtml('width: 82%')
        ->assertSeeHtml('left: 90%');
});

it('renders the empty state when the bullet has no scale', function (): void {
    Livewire::test(EmptyBulletWidget::class)
        ->assertSee('No data');
});
