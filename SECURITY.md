# Security Policy

## Supported versions

The latest released minor version receives security updates.

## Reporting a vulnerability

If you discover a security vulnerability, please email
**alexandre@laboiteacode.fr** instead of opening a public issue. You will
receive a response as quickly as possible. Please do not disclose the issue
publicly until it has been addressed.

## Security notes for this package

This package renders data that the application provides. To keep your dashboards
safe:

- All labels, descriptions, badges and metadata are escaped by default. The
  package never renders raw HTML from your data.
- The package never runs a query and never stores closures or Eloquent models
  in the public Livewire state.
- URLs are rendered as provided. Validate and generate them with Laravel and
  Filament helpers (for example `route()` or `Resource::getUrl()`).
