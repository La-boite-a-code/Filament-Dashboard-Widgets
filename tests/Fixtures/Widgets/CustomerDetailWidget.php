<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets;

use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Data\Detail;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\DetailListWidget;

class CustomerDetailWidget extends DetailListWidget
{
    protected ?string $heading = 'Customer';

    protected function getDetails(): array
    {
        return [
            Detail::make('Status', 'Active')->badge('Active')->badgeColor('success')->icon('heroicon-o-signal'),
            Detail::make('Plan', 'Pro')->icon('heroicon-o-star'),
            Detail::make('MRR', 4900)->formatUsing(fn (int $value): string => Number::currency($value, 'EUR', 'en')),
            Detail::make('Website', 'example.test')->url('https://example.test'),
            Detail::make('Notes', null),
        ];
    }
}
