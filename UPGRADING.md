# Upgrade guide

This document describes the changes required to upgrade between major versions
of the package. While the package is on the `0.x` line, minor releases may
introduce breaking changes; they will always be listed here.

## Filament compatibility

The package supports Filament 4 and Filament 5 from the same code base. No
change is required on your side when you upgrade Filament from 4 to 5, as long
as your own widgets keep extending the abstract widgets shipped by the package.

## Upgrading to 0.x

The package follows the abstract widget pattern: your application widgets extend
`MetricWidget`, `GoalProgressWidget`, `BreakdownWidget`, `RecentItemsWidget` or
`TrendWidget`. If a future release renames or reshapes a public method on those
classes or on the data objects, the migration steps will be documented in this
section.
