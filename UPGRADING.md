# Upgrade guide

This document describes the changes required to upgrade between major versions
of the package. The package follows semantic versioning: breaking changes only
land in a new major version, and they will always be listed here.

## Filament compatibility

The package supports Filament 4 and Filament 5 from the same code base. No
change is required on your side when you upgrade Filament from 4 to 5, as long
as your own widgets keep extending the abstract widgets shipped by the package.

## Upgrading to 1.x

1.0.0 is the initial stable release, so there is nothing to migrate from. The
package follows the abstract widget pattern: your application widgets extend
one of the fifteen abstract widgets shipped by the package (`MetricWidget`,
`GoalProgressWidget`, `TimelineWidget` and so on). If a future major release
renames or reshapes a public method on those classes or on the data objects,
the migration steps will be documented in this section.

### Published views in 1.0.1

Nothing is required: 1.0.1 has no breaking change. If you published the
package views, they keep working, but they miss two small markup changes that
1.0.1 made to match native Filament widgets. To pick them up, apply them to
your copies in `resources/views/vendor/filament-dashboard-widgets`:

- In the metric, goal progress, recent items, timeline and card views, the
  shade list passed to `get_color_css_variables()` becomes
  `[100, 400, 500, 600]` instead of `[400, 500, 600]`. Without it, icon
  backgrounds keep their 1.0.0 tint instead of the native `color-100` one.
- In the metric and card views, the description paragraph uses the
  `fi-fdw-description` class instead of `fi-fdw-muted`, which renders it at
  the native `0.875rem` instead of `0.75rem`.

If you never customised them, delete your copies instead: the package views
are used again automatically.
