<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptySegmentBarWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\TrafficSegmentWidget;
use Livewire\Livewire;

it('renders a single stacked track with a labelled legend', function (): void {
    Livewire::test(TrafficSegmentWidget::class)
        ->assertSee('Traffic by source')
        ->assertSee('Organic')
        ->assertSee('5,400')
        ->assertSee('54%')
        ->assertSeeHtml('fi-fdw-segbar__segment')
        ->assertSeeHtml('width: 54%');
});

it('renders the empty state when there are no segments', function (): void {
    Livewire::test(EmptySegmentBarWidget::class)
        ->assertSee('No data');
});
