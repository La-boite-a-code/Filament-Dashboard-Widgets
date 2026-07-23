<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\FunnelStage;

it('scales a stage relative to the top value', function (): void {
    expect(FunnelStage::make('Signups', 50)->getBarWidth(100.0))->toBe(50.0)
        ->and(FunnelStage::make('Signups', 50)->getBarWidth(0.0))->toBe(0.0)
        ->and(FunnelStage::make('Signups', 150)->getBarWidth(100.0))->toBe(100.0);
});

it('formats the stage value', function (): void {
    $stage = FunnelStage::make('Signups', 1280)
        ->formatUsing(fn (float $value): string => number_format($value));

    expect($stage->getLabel())->toBe('Signups')
        ->and($stage->getValue())->toBe(1280.0)
        ->and($stage->getFormattedValue())->toBe('1,280');
});
