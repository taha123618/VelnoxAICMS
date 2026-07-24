---
name: phpstan-static-analysis
description: Run static analysis, check type coverage, resolve PHPStan/Larastan errors. Use when running composer phpstan, analyzing types, or fixing phpstan.neon config.
---

# PHPStan & Larastan Static Analysis

This project uses **Larastan v3** (PHPStan extension for Laravel) at level **5** with custom ignore patterns.

## Running PHPStan

```bash
# Via composer script (recommended):
composer phpstan

# Run full analyse (PHPStan + Rector together):
composer run analyse

# Direct binary:
vendor/bin/phpstan analyse --memory-limit=2G
```

## Configuration

Config lives in `phpstan.neon` at the project root.

```neon
includes:
    - vendor/larastan/larastan/extension.neon
    - vendor/nesbot/carbon/extension.neon

parameters:
    paths:
        - app/
        - database/
        - routes/
        - Modules/

    level: 5

    reportUnmatchedIgnoredErrors: false

    ignoreErrors:
        - '#Access to an undefined property .*#'
        - '#Relation .* is not found in .* model.#'
        - '#expects .* given.#'
        - '#never returns .* so it can be removed from the return type.#'
        - '#contains unresolvable type.#'
        - '#Expression on left side of \?\? is not nullable.#'
        - '#Negated boolean expression is always false.#'
        - '#Call to an undefined method Illuminate\\Database\\Eloquent\\Builder::(withTrashed|onlyTrashed)\(\).#'

    excludePaths:
        - vendor/
        - storage/
        - bootstrap/cache/
        - '*/tests/*'
```

## Key Rules

- `reportUnmatchedIgnoredErrors: false` — prevents errors when an ignore pattern doesn't match anything
- Tests are excluded from PHPStan analysis (`*/tests/*`)
- `bootstrap/cache/` is excluded as it's auto-generated

## Best Practices

- Fix type hints and return type declarations before ignoring errors
- Use PHPDoc `@param array{id: int, name: string}` shapes for complex arrays
- Return `null` instead of `false` when the return type is `?string`
- Use `?Type $prop = null` for nullable static properties
- Prefer `new Model()` over `Model::make()` (raises Larastan warning)
- Never use `Model::make()` — use `new Model(['attr' => 'val'])` instead

## Common Fixes

| PHPStan Error | Fix |
|---|---|
| `Method should return string\|null but returns false` | Change `return false` → `return null` |
| `Model::make() performs unnecessary work` | Use `new Model([...])` |
| `instanceof always true` | Declare property as nullable with `= null` |
| `Caught class X not found` | Add to `ignoreErrors` in phpstan.neon |
| `createToken()/tokens() not found on User` | Sanctum dynamic methods — add to `ignoreErrors` |
