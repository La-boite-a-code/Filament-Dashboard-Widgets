# Changelog

All notable changes to `filament-dashboard-widgets` will be documented in this
file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2026-07-23

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
