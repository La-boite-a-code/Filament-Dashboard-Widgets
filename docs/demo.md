# Demo dashboard

This page shows a demo dashboard that uses a selection of the widgets with
fictional data, so you can see how they fit together. The rendered result is
shown in the screenshots in the project [README](../README.md).

A static, self contained HTML preview is also available at
[`art/preview.html`](../art/preview.html).

## The widgets

```php
use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Data\BreakdownItem;
use LaBoiteACode\FilamentDashboardWidgets\Data\Bullet;
use LaBoiteACode\FilamentDashboardWidgets\Data\Composition;
use LaBoiteACode\FilamentDashboardWidgets\Data\CompositionSlice;
use LaBoiteACode\FilamentDashboardWidgets\Data\Detail;
use LaBoiteACode\FilamentDashboardWidgets\Data\Goal;
use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Data\RecentItem;
use LaBoiteACode\FilamentDashboardWidgets\Data\Trend;
use LaBoiteACode\FilamentDashboardWidgets\Data\TrendPoint;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\BreakdownWidget;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\BulletWidget;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\CompositionWidget;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\DetailListWidget;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\GoalProgressWidget;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\MetricWidget;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\RecentItemsWidget;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\TrendWidget;

class DemoRevenueWidget extends MetricWidget
{
    protected function getMetric(): Metric
    {
        return Metric::make('Monthly revenue', 24_850)
            ->formatUsing(fn (int $value) => Number::currency($value, 'EUR'))
            ->description('Compared to last month')
            ->trend(12.4)
            ->icon('heroicon-o-banknotes')
            ->color('primary')
            ->sparkline([12, 14, 13, 18, 20, 19, 24]);
    }
}

class DemoRefundsWidget extends MetricWidget
{
    protected function getMetric(): Metric
    {
        return Metric::make('Refunds', 1_240)
            ->formatUsing(fn (int $value) => Number::currency($value, 'EUR'))
            ->description('Lower is better')
            ->trend(6.2)
            ->lowerIsBetter()
            ->icon('heroicon-o-receipt-refund')
            ->color('primary')
            ->sparkline([10, 12, 11, 14, 13, 16, 17]);
    }
}

class DemoGoalWidget extends GoalProgressWidget
{
    protected function getGoal(): Goal
    {
        return Goal::make('Monthly goal', current: 72_500, target: 100_000)
            ->formatUsing(fn ($value) => Number::currency($value, 'EUR'))
            ->deadline(now()->endOfMonth())
            ->color('primary')
            ->showRemaining()
            ->showPercentage();
    }
}

class DemoBreakdownWidget extends BreakdownWidget
{
    protected ?string $heading = 'Customers by status';

    protected function getItems(): array
    {
        return [
            BreakdownItem::make('Active', 148)->color('success')->icon('heroicon-o-check-circle'),
            BreakdownItem::make('Pending', 32)->color('warning')->icon('heroicon-o-clock'),
            BreakdownItem::make('Inactive', 12)->color('gray')->icon('heroicon-o-minus-circle'),
        ];
    }
}

class DemoRecentOrdersWidget extends RecentItemsWidget
{
    protected ?string $heading = 'Recent orders';

    protected function getItems(): array
    {
        return [
            RecentItem::make('#1042', 'Ada Lovelace')->meta('120.00 EUR')->badge('Paid')->badgeColor('success'),
            RecentItem::make('#1041', 'Alan Turing')->meta('80.00 EUR')->badge('Pending')->badgeColor('warning'),
            RecentItem::make('#1040', 'Grace Hopper')->meta('240.00 EUR')->badge('Refunded')->badgeColor('gray'),
        ];
    }
}

class DemoTrendWidget extends TrendWidget
{
    protected function getTrend(): Trend
    {
        return Trend::make('New customers')
            ->value(184)
            ->comparison(16.8)
            ->points([
                TrendPoint::make('Mon', 18),
                TrendPoint::make('Tue', 24),
                TrendPoint::make('Wed', 21),
                TrendPoint::make('Thu', 30),
                TrendPoint::make('Fri', 28),
                TrendPoint::make('Sat', 36),
                TrendPoint::make('Sun', 33),
            ])
            ->type('area')
            ->color('primary');
    }
}

class DemoSatisfactionWidget extends BulletWidget
{
    protected function getBullet(): Bullet
    {
        return Bullet::make('Customer satisfaction', 82)
            ->target(90)
            ->max(100)
            ->comparative(75)
            ->ranges([50, 75])
            ->formatUsing(fn (float $value) => "{$value}%");
    }
}

class DemoCustomerWidget extends DetailListWidget
{
    protected ?string $heading = 'Customer';

    protected function getDetails(): array
    {
        return [
            Detail::make('Status', 'Active')->badge('Active')->badgeColor('success'),
            Detail::make('Plan', 'Pro')->icon('heroicon-o-star'),
            Detail::make('MRR', 4_900)->formatUsing(fn (int $value) => Number::currency($value, 'EUR')),
            Detail::make('Created', now()->subMonths(6)->translatedFormat('d M Y')),
        ];
    }
}

class DemoRevenueByChannelWidget extends CompositionWidget
{
    protected function getComposition(): Composition
    {
        return Composition::make('Revenue by channel')
            ->type('doughnut')
            ->formatUsing(fn (float $value) => Number::currency($value, 'EUR'))
            ->slices([
                CompositionSlice::make('Direct', 12_000)->color('primary'),
                CompositionSlice::make('Marketplace', 8_000)->color('success'),
                CompositionSlice::make('Partners', 5_000)->color('gray'),
            ]);
    }
}
```

## The dashboard page

```php
use Filament\Pages\Dashboard as BaseDashboard;

class DemoDashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            DemoRevenueWidget::class,
            DemoRefundsWidget::class,
            DemoGoalWidget::class,
            DemoBreakdownWidget::class,
            DemoRecentOrdersWidget::class,
            DemoTrendWidget::class,
            DemoSatisfactionWidget::class,
            DemoCustomerWidget::class,
            DemoRevenueByChannelWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 3;
    }
}
```
