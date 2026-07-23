<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyComparisonChartWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\RevenueVsTargetWidget;
use Livewire\Livewire;

it('renders a multi series line chart with resolved colours', function (): void {
    Livewire::test(RevenueVsTargetWidget::class)
        ->assertSee('Revenue vs target')
        ->assertSee('Actual')
        ->assertSee('Target')
        ->assertSeeHtml('data-chart-type="line"')
        ->assertSeeHtml('oklch');
});

it('renders the native empty state when there are no series', function (): void {
    Livewire::test(EmptyComparisonChartWidget::class)
        ->assertSee('No data');
});
