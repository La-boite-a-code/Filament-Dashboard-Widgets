<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\VarianceItem;

it('derives the change from the previous value or uses an explicit change', function (): void {
    expect(VarianceItem::make('A', 120)->previous(100)->getChange())->toBe(20.0)
        ->and(VarianceItem::make('A', 120)->previous(100)->getPercentageChange())->toBe(20.0)
        ->and(VarianceItem::make('B', 0)->change(-15)->getChange())->toBe(-15.0)
        ->and(VarianceItem::make('C', 50)->getChange())->toBe(0.0)
        ->and(VarianceItem::make('C', 50)->getPercentageChange())->toBeNull();
});

it('resolves direction and colour honouring lowerIsBetter', function (): void {
    expect(VarianceItem::make('A', 1)->change(10)->getDirection())->toBe('up')
        ->and(VarianceItem::make('A', 1)->change(10)->getChangeColor())->toBe('success')
        ->and(VarianceItem::make('A', 1)->change(-10)->getChangeColor())->toBe('danger')
        ->and(VarianceItem::make('A', 1)->change(10)->lowerIsBetter()->getChangeColor())->toBe('danger')
        ->and(VarianceItem::make('A', 1)->change(0)->getDirection())->toBe('neutral');
});

it('formats the signed change and computes the bar width', function (): void {
    expect(VarianceItem::make('A', 0)->change(-1240)->getFormattedChange())->toBe('-1,240')
        ->and(VarianceItem::make('A', 0)->change(620)->getFormattedChange())->toBe('+620')
        ->and(VarianceItem::make('A', 0)->change(-1240)->getBarWidth(2480.0))->toBe(50.0)
        ->and(VarianceItem::make('A', 0)->change(0)->getBarWidth(0.0))->toBe(0.0);
});
