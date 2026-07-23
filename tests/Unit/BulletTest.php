<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Data\Bullet;

it('computes value, target and comparative percentages against the scale', function (): void {
    $bullet = Bullet::make('CSAT', 82)->target(90)->max(100)->comparative(75);

    expect($bullet->getScaleMax())->toBe(100.0)
        ->and($bullet->getValuePercentage())->toBe(82.0)
        ->and($bullet->getTargetPercentage())->toBe(90.0)
        ->and($bullet->getComparativePercentage())->toBe(75.0);
});

it('derives the scale from the largest input when no max is set', function (): void {
    $bullet = Bullet::make('x', 60)->target(120);

    expect($bullet->getScaleMax())->toBe(120.0)
        ->and($bullet->getValuePercentage())->toBe(50.0);
});

it('splits the track into bands from ascending ranges', function (): void {
    $bands = Bullet::make('x', 50)->max(100)->ranges([80, 50])->getBands();

    expect($bands)->toBe([
        ['width' => 50.0, 'shade' => 1],
        ['width' => 30.0, 'shade' => 2],
        ['width' => 20.0, 'shade' => 3],
    ]);
});

it('reports status and colour honouring lowerIsBetter', function (): void {
    expect(Bullet::make('x', 82)->target(90)->getStatus())->toBe('below')
        ->and(Bullet::make('x', 82)->target(90)->getStatusColor())->toBe('warning')
        ->and(Bullet::make('x', 95)->target(90)->getStatus())->toBe('above')
        ->and(Bullet::make('x', 95)->target(90)->getStatusColor())->toBe('success')
        ->and(Bullet::make('x', 3)->target(5)->lowerIsBetter()->getStatusColor())->toBe('success')
        ->and(Bullet::make('x', 7)->target(5)->lowerIsBetter()->getStatusColor())->toBe('warning')
        ->and(Bullet::make('x', 5)->getStatus())->toBe('none');
});

it('clamps the value bar to the explicit max', function (): void {
    expect(Bullet::make('x', 150)->max(100)->getValuePercentage())->toBe(100.0);
});

it('has a zero scale when nothing is provided', function (): void {
    expect(Bullet::make('x')->getScaleMax())->toBe(0.0)
        ->and(Bullet::make('x')->getValuePercentage())->toBe(0.0)
        ->and(Bullet::make('x')->getBands())->toBe([]);
});
