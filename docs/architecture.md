# Architecture

This document explains the design decisions behind Filament Dashboard Widgets.
It is aimed at contributors and at developers who want to understand how the
package behaves before extending it.

## Inheritance strategy

Every widget is an abstract class that the application extends with its own
data. The package never ships concrete, ready to register widgets because the
data always belongs to the application.

- `MetricWidget`, `GoalProgressWidget`, `BreakdownWidget` and
  `RecentItemsWidget` extend a shared `DashboardWidget` base which itself
  extends Filament's native `Filament\Widgets\Widget`. The base only adds the
  cross cutting concerns (`CanPoll`, `HasEmptyState`) and never reimplements
  anything Filament already provides, such as `getColumnSpan()`.
- `RecentItemsWidget` and `BreakdownWidget` additionally implement Filament's
  `HasActions` and `HasSchemas` contracts through the native
  `InteractsWithActions` and `InteractsWithSchemas` traits. This gives their
  "View all" and "View more" footers real Filament actions instead of a
  parallel action system.
- `TrendWidget` extends `Filament\Widgets\ChartWidget` directly so the chart is
  drawn by Filament's own Chart.js integration. No JavaScript ships with this
  package.

The data itself lives in small, fluent value objects (`Metric`, `Goal`,
`BreakdownItem`, `RecentItem`, `Trend`, `TrendPoint`, `WidgetAction`). They keep
the widget classes thin and are trivial to unit test in isolation.

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
Livewire properties. Instead, the concrete widget builds its data inside
`getMetric()`, `getGoal()`, `getItems()` or `getTrend()`, and the base widget
exposes only the resolved, primitive friendly values to the Blade view through
`getViewData()`.

This has two consequences:

- The Livewire state stays tiny, which avoids serialising heavy models or
  closures (see the security notes in the README).
- Polling simply re renders the widget, which calls `getViewData()` again and
  recomputes everything from the developer supplied source.

## Styling strategy

The widgets are styled with a single, self contained stylesheet built on
Filament design tokens (`--gray-*`, `--primary-*`, the accent colour variables
resolved through `Filament\Support\get_color_css_variables()`). The stylesheet
is injected inline through the `PanelsRenderHook::STYLES_AFTER` render hook, so
the package works out of the box with no asset publishing and no front-end build
step. Colours are never hard coded: they always resolve to the panel's
registered Filament colours, so light and dark themes and the panel accent
colour are respected automatically.

Generic building blocks (empty states, badges, icons, links, actions) use
Filament's own Blade components. Only the widget specific visuals that Filament
has no primitive for (the metric value, the sparkline, the progress and
breakdown bars) are drawn with the package stylesheet.

## Performance limits

- No query is ever run by the package. The developer supplies the data.
- Arrays and collections are normalised once per render.
- Sparklines are capped (30 points by default, configurable) and rendered as a
  static inline SVG polyline, computed in PHP.
- The trend chart data is prepared entirely in PHP before being handed to
  Filament's chart component.
