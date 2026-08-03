---
name: rector-refactoring
description: Perform automated PHP refactoring, upgrade PHP/Laravel syntax, and apply Rector rules. Use when running composer rector, processing code upgrades, or modifying rector.php config.
---

# Rector Code Refactoring

This project uses **Rector v2** with **driftingly/rector-laravel** targeting **PHP 8.5** and **Laravel 13**.

## Running Rector

```bash
# Dry-run (preview changes only, no write):
vendor/bin/rector process --dry-run

# Apply refactoring (via composer script):
composer rector

# Run full analyse (PHPStan + Rector):
composer run analyse
```

## Configuration

Config lives in `rector.php` at the project root:

```php
return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/Modules',
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withSkip([
        __DIR__.'/bootstrap/cache',  // Auto-generated, never touch
        __DIR__.'/vendor',
        __DIR__.'/public',
        __DIR__.'/storage',
    ])
    ->withPhpSets()               // PHP 8.5 syntax rules
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        naming: true,
        earlyReturn: true
    )
    ->withSets([
        LaravelSetList::LARAVEL_130,         // Laravel 13 upgrade rules
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_COLLECTION,
    ]);
```

## Critical Rules

- **NEVER** include `bootstrap/cache/` in paths — it holds auto-generated files that Rector will corrupt (use `->withSkip()`)
- Always run `php artisan optimize:clear` after Rector to regenerate cache files cleanly
- After Rector runs, always run `./vendor/bin/pint --dirty` to fix any code style issues it introduces

## Workflow

1. `composer run analyse` — runs PHPStan then Rector
2. `./vendor/bin/pint --dirty` — fix style after Rector
3. `composer test` — verify all tests still pass
4. Review any unexpected diffs before committing

## Available Laravel Sets (LaravelSetList)

| Constant | Purpose |
|---|---|
| `LARAVEL_130` | Laravel 13.x upgrade rules |
| `LARAVEL_CODE_QUALITY` | Code quality improvements |
| `LARAVEL_COLLECTION` | Collection helper modernization |
| `LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER` | Explicit query builder |

## Common Transformations

- `array()` → `[]` (short array syntax)
- String class names → `::class` constants (`'App\Models\User'` → `App\Models\User::class`)
- `Model::make([])` → `new Model([])` (avoid unnecessary factory overhead)
- Adds return type declarations to methods
- Removes dead code and unreachable conditions
