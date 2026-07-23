<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\EmptyFunnelWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\SignupFunnelWidget;
use Livewire\Livewire;

it('renders a funnel with tapering bars, step conversions and an overall rate', function (): void {
    Livewire::test(SignupFunnelWidget::class)
        ->assertSee('Signup funnel')
        ->assertSee('Visitors')
        ->assertSee('4,200')
        ->assertSee('Signups')
        ->assertSeeHtml('width: 100%')
        ->assertSee('30.5%')
        ->assertSee('Overall conversion: 5%');
});

it('renders the empty state when there are no stages', function (): void {
    Livewire::test(EmptyFunnelWidget::class)
        ->assertSee('No data');
});
