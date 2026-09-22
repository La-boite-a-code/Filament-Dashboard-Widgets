# Architecture

This document explains the design decisions behind Filament Dashboard Widgets.
It is aimed at contributors and at developers who want to understand how the
package behaves before extending it.

## Inheritance strategy

Every widget is an abstract class that the application extends with its own
data. The package never ships concrete, ready to register widgets because the
data always belongs to the application.

- The twelve non chart widgets (`MetricWidget`, `GoalProgressWidget`,
  `BreakdownWidget`, `RecentItemsWidget`, `DetailListWidget`, `BulletWidget`,
  `FunnelWidget`, `TimelineWidget`, `VarianceWidget`, `SegmentBarWidget`,
  `UsageLimitsWidget` and `CardWidget`) extend a shared `DashboardWidget` base
  which itself extends Filament's native `Filament\Widgets\Widget`. The base
  only adds the cross cutting concerns (`CanPoll`, `HasEmptyState`) and never
  reimplements anything Filament already provides, such as `getColumnSpan()`.
- `RecentItemsWidget`, `BreakdownWidget` and `TimelineWidget` additionally
  implement Filament's `HasActions` and `HasSchemas` contracts through the
  native `InteractsWithActions` and `InteractsWithSchemas` traits. This gives
  their "View all" and "View more" footers real Filament actions instead of a
  parallel action system.
- The three chart widgets (`TrendWidget`, `CompositionWidget` and
  `ComparisonChartWidget`) extend `Filament\Widgets\ChartWidget` directly so
  the charts are drawn by Filament's own Chart.js integration. No JavaScript
  ships with this package.

The data itself lives in small, fluent value objects (`Metric`, `Goal`,
`BreakdownItem`, `RecentItem`, `Trend`, `TrendPoint`, `Composition`,
`CompositionSlice`, `Detail`, `Bullet`, `FunnelStage`, `TimelineEvent`,
`VarianceItem`, `UsageLimit`, `ChartSeries`, `Card` and `WidgetAction`). They
keep the widget classes thin and are trivial to unit test in isolation.

## Callback strategy

Every setter accepts either a static value or a `Closure`. Values are resolved
lazily, at render time, through the tiny `EvaluatesClosures` concern:

```php
Metric::make('Revenue', 24_850)
    ->color(fn () => $this->isHealthy() ? 'success' : 'danger');
```

A closure passed to a setter is evaluated with no arguments, except
`formatUsing()` which receives the raw value so it can format it:

```php
->formatUsing(fn (int $value) => Number::currency($value, 'EUR'));
```

The evaluation helper is intentionally minimal rather than a copy of Filament's
dependency injecting evaluator: the value objects have no container context to
inject, so a simple, predictable and fully testable resolver is a better fit.

## Livewire strategy

Widgets never store the data objects, closures or Eloquent models in public
Livewire properties. Instead, the concrete widget builds its data inside its
data method (`getMetric()`, `getGoal()`, `getItems()`, `getEvents()` and so
on), and the base widget exposes only the resolved, primitive friendly values
to the Blade view through `getViewData()`.

This has two consequences:

- The Livewire state stays tiny, which avoids serialising heavy models or
  closures (see the security notes in the README).
- Polling simply re renders the widget, which calls `getViewData()` again and
  recomputes everything from the developer supplied source.

## Styling strategy

The widgets are styled with a single, self contained stylesheet built on
Filament design tokens (`--gray-*`, `--primary-*`, the accent colour variables
resolved through `Filament\Support\get_color_css_variables()`). Colours are
never hard coded: they always resolve to the panel's registered Filament
colours, so light and dark themes and the panel accent colour are respected
automatically.

The surfaces reuse the exact values Filament compiles for its own widgets: the
card is a white (`gray-900` in dark mode) surface with a 1px `gray-950` ring at
5% opacity (`white` at 10% in dark mode) over the two layer `shadow-sm`, a
`0.75rem` radius and `1.5rem` of padding, like the stats overview stat and the
section container. Headings, values, descriptions, icon backgrounds, row
dividers and focus rings follow the same native scale. `StylesheetTest` pins
these values so a change to the stylesheet cannot drift away from Filament
silently.

The stylesheet is registered with `FilamentAsset` as a `Css` asset marked as
loaded on request, so `php artisan filament:assets` publishes it but Filament
never links it on its own. The `PanelsRenderHook::STYLES_AFTER` render hook
then links the published file when it exists, giving a regular browser cached
request, and falls back to inlining the stylesheet when it does not. The
package therefore works out of the box with no asset publishing and no
front-end build step, without costing a 404 per page when the asset was never
published.

Generic building blocks (empty states, badges, icons, links, actions) use
Filament's own Blade components. Only the widget specific visuals that Filament
has no primitive for (the metric value, the sparkline, the progress, breakdown
and segment tracks, the bullet graph, the funnel, the timeline rail and the
usage meters) are drawn with the package stylesheet.

## Performance limits

- No query is ever run by the package. The developer supplies the data.
- Arrays and collections are normalised once per render.
- Sparklines are capped (30 points by default, configurable) and rendered as a
  static inline SVG polyline, computed in PHP.
- The trend chart data is prepared entirely in PHP before being handed to
  Filament's chart component.
