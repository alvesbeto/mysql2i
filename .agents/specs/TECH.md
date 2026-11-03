---
title: "Technical architecture"
description: "ROUTING: Read for stack, layout, autoload, and verify commands. Skip for a one-line comment fix in src/."
tags: [tech, architecture]
---

# Technical architecture

## Runtime

| Item | Value |
|------|--------|
| PHP | `^8.0` (`composer.json`) |
| Extension | `ext-mysqli` (required) |
| Package type | `library` |

No Docker or database in this repo. Consumers bring their own MySQL/MariaDB.

## Layout

| Path | Role |
|------|------|
| `src/mysql2i.class.php` | Static class `mysql2i` (mysqli backend) |
| `src/mysql2i.func.php` | Global `mysql_*` functions + `MYSQL_*` constants |
| `docs/API.md` | Human API reference (not duplicated in `.agents/`) |
| `README.md` | Install and usage |
| `.agents/` | Agent context (OKF + SDD; method registry in knowledge) |

## Autoload

```json
"classmap": ["src/mysql2i.class.php"],
"files": ["src/mysql2i.func.php"]
```

Class loads on first `mysql2i::` use; functions load at autoload bootstrap.

## Encapsulation

`mysqli` / `mysqli_result` appear **only** under `src/`. See [ADR-0004](../adrs/ADR-0004-mysqli-only-in-src.md).

## Verify

Full commands: [../runbooks/validate.md](../runbooks/validate.md).

```bash
composer validate --strict
composer dump-autoload -o
php -l src/mysql2i.class.php
php -l src/mysql2i.func.php
php -r "require 'vendor/autoload.php'; var_dump(function_exists('mysql_connect'), class_exists('mysql2i'));"
```

## Tests

No PHPUnit suite yet. Adding tests is ASK FIRST unless the user requests them.

Agent memory: [../knowledge/index.md](../knowledge/index.md). Module map: [CAPABILITY_MAP.md](CAPABILITY_MAP.md).
