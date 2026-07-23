<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Bullet;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\BulletWidget;

class EmptyBulletWidget extends BulletWidget
{
    protected function getBullet(): Bullet
    {
        return Bullet::make('Uninitialised');
    }
}
