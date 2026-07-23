<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyVarianceWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\TopMoversVarianceWidget;
use Livewire\Livewire;

it('renders diverging change bars with signed deltas', function (): void {
    Livewire::test(TopMoversVarianceWidget::class)
        ->assertSee('Top movers this week')
        ->assertSee('Enterprise')
        ->assertSee('+600')
        ->assertSee('-300')
        ->assertSeeHtml('fi-fdw-variance__bar--pos')
        ->assertSeeHtml('fi-fdw-variance__bar--neg');
});

it('renders the empty state when there are no items', function (): void {
    Livewire::test(EmptyVarianceWidget::class)
        ->assertSee('No data');
});
