<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\MonthlyGoalWidget;
use Livewire\Livewire;

it('renders a goal with a progress bar and percentage', function (): void {
    Livewire::test(MonthlyGoalWidget::class)
        ->assertSee('Monthly goal')
        ->assertSeeHtml('role="progressbar"')
        ->assertSeeHtml('width: 72.5%')
        ->assertSee('72.5%')
        ->assertSee('Remaining');
});
