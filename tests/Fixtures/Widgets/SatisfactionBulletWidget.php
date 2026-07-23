<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Bullet;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\BulletWidget;

class SatisfactionBulletWidget extends BulletWidget
{
    protected function getBullet(): Bullet
    {
        return Bullet::make('Customer satisfaction', 82)
            ->target(90)
            ->max(100)
            ->comparative(75)
            ->ranges([50, 75])
            ->formatUsing(fn (float $value): string => rtrim(rtrim(number_format($value, 1), '0'), '.').'%')
            ->description('Rolling 30 days');
    }
}
