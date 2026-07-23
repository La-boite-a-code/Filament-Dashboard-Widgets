# Contributing

Thank you for considering contributing to Filament Dashboard Widgets.
Contributions are welcome and appreciated.

## Reporting issues

Please use the issue templates and include the package, Filament, Laravel and
PHP versions, plus a minimal reproduction when possible.

## Development setup

```bash
git clone https://github.com/la-boite-a-code/filament-dashboard-widgets.git
cd filament-dashboard-widgets
composer install
```

## Quality gates

Every change must keep the following green:

```bash
composer test      # Pest test suite
vendor/bin/pint    # Laravel Pint (code style)
composer analyse   # PHPStan static analysis
```

The continuous integration pipeline runs the test suite against every supported
combination of PHP, Laravel and Filament, so please make sure your change works
on both Filament 4 and Filament 5.

## Guidelines

- Keep each widget usable on its own.
- Do not hard code colours: always rely on Filament design tokens.
- Do not add front-end dependencies or custom JavaScript without a written
  justification.
- Prefer extending Filament's native components and actions over reimplementing
  them.
- Add tests for any new behaviour and update the documentation and the
  `CHANGELOG.md`.

## Pull requests

- Create a topic branch from `main`.
- Keep the pull request focused on a single concern.
- Fill in the pull request template.
