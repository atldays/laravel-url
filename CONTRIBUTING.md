# Contributing

Thanks for contributing to `atldays/laravel-url`.

This document describes the basic workflow for contributing to the package, including development setup, coding standards, testing, formatting, and commit conventions.

## Requirements

Before you start, make sure you have:

- PHP `^8.2`
- Composer
- a Laravel-compatible local environment or Docker-based PHP workflow

## Local Setup

Clone the repository and install dependencies:

```bash
composer install
```

If you want to enable the repository Git hooks, run:

```bash
composer hooks:install
```

## Development Workflow

When working on a change:

1. Create a dedicated branch.
2. Keep changes focused and small when possible.
3. Add or update tests for behavioral changes.
4. Run formatting and tests before opening a pull request.
5. Update documentation when the public API or developer workflow changes.

## Code Style

This project uses [Laravel Pint](https://laravel.com/docs/pint) for formatting.

Check formatting:

```bash
composer format:test
```

Automatically fix formatting:

```bash
composer format
```

Please do not submit pull requests with failing formatting checks.

## Testing

This project uses PHPUnit for automated testing.

Run the full test suite:

```bash
composer test
```

When adding or changing behavior, include tests that cover the new behavior or protect against regressions.

### Testing Expectations

- add unit tests for isolated logic
- add feature tests for Laravel integration behavior
- keep tests focused and readable
- prefer explicit assertions over broad snapshots

## Commit Convention

This repository uses Conventional Commits.

Please write commit messages in the following format:

```text
type(scope): short description
```

Examples:

```text
feat(factory): add header sanitizer profile support
fix(rule): handle browser urls in validation rule
docs(readme): document translation publishing
test(sanitizer): cover invalid utf8 edge cases
chore(ci): update test matrix
```

### Recommended Commit Types

- `feat`
- `fix`
- `docs`
- `refactor`
- `test`
- `chore`
- `build`
- `ci`

Keep commit messages short, specific, and written in the imperative mood.

## Pull Requests

Before opening a pull request, make sure:

- the branch is up to date with the target branch
- formatting passes
- tests pass
- documentation is updated when needed
- the pull request has a clear summary of what changed and why

Good pull requests usually include:

- a short problem statement
- a summary of the solution
- notes about any breaking or behavioral changes
- mention of new configuration, rules, casts, or public API additions

## Scope Of Changes

This package is focused on Laravel-friendly URL handling.

Please keep contributions aligned with the package scope:

- URL value objects
- Laravel integration
- validation rules
- request macros
- sanitizer pipelines
- `spatie/laravel-data` integration

Changes that introduce unrelated responsibilities should be discussed before implementation.

## Documentation

If your change affects package usage, please update the relevant documentation, especially:

- `README.md`
- config examples
- validation or integration examples

## Reporting Issues

When opening an issue, please include as much context as possible:

- Laravel version
- PHP version
- package version
- a short reproduction case
- expected behavior
- actual behavior

## Questions

If you are unsure whether a change fits the package direction, open an issue or discussion first before spending time on a larger implementation.
