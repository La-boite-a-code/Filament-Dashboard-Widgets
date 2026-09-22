# Changelog

All notable changes to `filament-dashboard-widgets` will be documented in this
file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2026-09-22

### Changed

- The stylesheet is now registered as a Filament asset, so
  `php artisan filament:assets` (also run by `filament:upgrade`) publishes it
  and the panel links it as a regular, browser cached file. It used to be
  inlined in full, about 28 KB, into the head of every panel page on every
  request. When the asset has not been published, the render hook still
  inlines it, so the package keeps working out of the box. The asset is marked
  as loaded on request so Filament never links a file that does not exist.
- The cards now reuse the values Filament compiles for its own widgets, so they
  no longer stand out next to a native stats overview or chart widget: a 1px
  `gray-950` ring at 5% (`white` at 10% in dark mode) over the two layer
  `shadow-sm` instead of an opaque `gray-200` border and a single shadow,
  section heading scale for titles (`1rem`) and descriptions (`0.875rem`),
  semibold headline values at a fixed `1.875rem` instead of bold values growing
  to `2.25rem` on wide screens, `color-100` icon backgrounds (`color-500` at 20%
  in dark mode), `gray-200` row dividers (`white` at 5% in dark mode) and a 2px
  `primary-600` focus ring. Clickable cards no longer lift or deepen their
  shadow on hover, as native clickable stats do not either. Views published
  before this release keep working: their icon backgrounds fall back to the
  previous tint. See the upgrade guide to bring them up to date.

### Fixed

- Numbers and percentages are now formatted with the application locale instead
  of always falling back to English. `Illuminate\Support\Number` defaults to the
  `en` locale and Laravel never syncs it with `app()->getLocale()`, so a French
  or Spanish panel rendered translated labels next to `1,234.5` instead of
  `1 234,5`. Every `Number::format()` and `Number::percentage()` call now passes
  the current locale.

### Security

- Colour names passed to `color()` and `badgeColor()` are restricted to
  `[a-zA-Z0-9_-]`, matching the character set Filament itself allows. These
  names are interpolated into a `style` attribute as CSS custom properties.
  Blade escaping prevented breaking out of the attribute, but colons,
  semicolons and parentheses survived it, so an application feeding untrusted
  input into a colour setter could append arbitrary CSS declarations to the
  element (external resource loading, overlays, defacement). A name that is
  left empty after sanitisation now falls back to the widget default.

## [1.0.0] - 2026-07-24

### Added

- `MetricWidget` with value formatting, trend semantics, `lowerIsBetter()`
  inversion, inline SVG sparkline, tooltip and clickable URL.
- `GoalProgressWidget` with progress bar, remaining amount, percentage,
  overflow handling, zero and negative target guards and optional deadline.
- `RecentItemsWidget` with avatar or icon, badge, metadata, per item URL and a
  native Filament "View all" action.
- `BreakdownWidget` with automatic or explicit percentages, proportional bars,
  optional sorting, a display limit and a native Filament "View more" action.
- `TrendWidget` extending Filament's `ChartWidget` with a summary value and a
  period comparison.
- `CompositionWidget` extending Filament's `ChartWidget` for part to whole
  doughnut, pie and polarArea charts, with slice colours resolved from the
  panel's registered Filament colours.
- `DetailListWidget` rendering a semantic key to value description list with
  optional badge, icon, link and placeholder per row.
- `BulletWidget` rendering a bullet graph: a value against a target with neutral
  qualitative bands, a target tick and an optional benchmark marker.
- `FunnelWidget` rendering an ordered conversion funnel with tapering bars, step
  conversion and an overall rate.
- `TimelineWidget` rendering an activity feed with a rail, relative times,
  optional day grouping and a "View all" Filament action.
- `VarianceWidget` rendering categories ranked by change as diverging bars
  around a zero axis.
- `SegmentBarWidget` rendering a whole as a single stacked track with a legend,
  reusing the `BreakdownItem` object.
- `UsageLimitsWidget` tracking consumption against quotas with one accessible
  meter per resource and an optional warning threshold.
- `ComparisonChartWidget` extending Filament's `ChartWidget` to plot several
  line or bar series against shared labels, with colours resolved from the
  panel's registered Filament colours.
- `CardWidget`, a flexible, customizable card with four layout variants (stat,
  icon, content and call to action), reusing Filament's native badge, icon, link
  and button components.
- Fluent, typed data objects: `Metric`, `Goal`, `BreakdownItem`, `RecentItem`,
  `Trend`, `TrendPoint`, `Composition`, `CompositionSlice`, `Detail`, `Bullet`,
  `FunnelStage`, `TimelineEvent`, `VarianceItem`, `UsageLimit`, `ChartSeries`,
  `Card` and `WidgetAction`.
- Optional colour token on `WidgetAction`, used to style call to action buttons.
- English and French translations.
- A self contained, theme aware stylesheet injected inline, with no asset
  publishing or front-end build step.
- Accessible by default: WCAG AA contrast in light and dark themes, keyboard
  focus styles on every clickable row, screen reader labels and
  `prefers-reduced-motion` support.
