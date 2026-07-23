---
name: phpstan-static-analysis
description: Run static analysis, check type coverage, resolve PHPStan/Larastan errors. Use when running composer phpstan, analyzing types, or fixing phpstan.neon config.
---

# PHPStan & Larastan Static Analysis

Larastan provides static analysis for Laravel, helping catch bugs before they run.

## Running PHPStan

Run static analysis using composer script:
```bash
composer phpstan
```
Or directly via the binary:
```bash
vendor/bin/phpstan analyse
```

## Configuration

The configuration is located in `phpstan.neon` in the project root.
Key configurations:
- `paths`: Directories to analyze (typically `app/`, `database/`, `routes/`, `Modules/`).
- `level`: Strictness level (0 to 10, current is 5).
- `excludePaths`: Paths to ignore.

## Best Practices

- Always fix type hints and return type declarations.
- Use PHPDoc blocks with array shape definitions for complex array structures:
  ```php
  /**
   * @param array{id: int, name: string} $data
   */
  ```
- Use Larastan-specific docstrings when resolving database model query builders.
