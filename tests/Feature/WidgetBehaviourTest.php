<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\MonthlyGoalWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\PollingMetricWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\RecentOrdersWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\RevenueMetricWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\UnsafeMetricWidget;
use Livewire\Livewire;

it('does not poll by default', function (): void {
    Livewire::test(RevenueMetricWidget::class)
        ->assertDontSeeHtml('wire:poll');
});

it('polls when a widget defines an interval', function (): void {
    Livewire::test(PollingMetricWidget::class)
        ->assertSeeHtml('wire:poll.30s');
});

it('escapes labels and descriptions by default', function (): void {
    Livewire::test(UnsafeMetricWidget::class)
        ->assertDontSeeHtml('<script>alert(1)</script>')
        ->assertDontSeeHtml('<b>injected</b>')
        ->assertSeeHtml('&lt;script&gt;');
});

it('renders the French translations for a recent items widget', function (): void {
    app()->setLocale('fr');

    Livewire::test(RecentOrdersWidget::class)
        ->assertSee('Voir tout');
});

it('renders the French translations for a goal widget', function (): void {
    app()->setLocale('fr');

    Livewire::test(MonthlyGoalWidget::class)
        ->assertSee('Restant');
});
