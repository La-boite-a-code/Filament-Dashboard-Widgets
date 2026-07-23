<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\ActivityTimelineWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyTimelineWidget;
use Livewire\Livewire;

afterEach(fn () => Carbon::setTestNow());

it('renders a grouped timeline with relative times and a view all action', function (): void {
    Carbon::setTestNow(Carbon::parse('2026-07-15 12:00:00'));

    Livewire::test(ActivityTimelineWidget::class)
        ->assertSee('Recent activity')
        ->assertSee('Order #1042 paid')
        ->assertSee('Paid')
        ->assertSee('Today')
        ->assertSee('Yesterday')
        ->assertSee('2 hours ago')
        ->assertSee('View all')
        ->assertSeeHtml('fi-fdw-timeline__marker');
});

it('renders the empty state when there are no events', function (): void {
    Livewire::test(EmptyTimelineWidget::class)
        ->assertSee('No data');
});
