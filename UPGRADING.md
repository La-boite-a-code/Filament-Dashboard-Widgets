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
