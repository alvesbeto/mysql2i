---
title: "Shim behavior contract"
description: "ROUTING: Read before editing src/mysql2i*.php. Defines invariants agents must not break silently."
tags: [behavior, mysql-shim]
---

# Shim behavior contract

Source of truth for code: `src/mysql2i.class.php`, `src/mysql2i.func.php`. This page is the **agent-facing** summary.

## Connection

| Invariant | Detail |
|-----------|--------|
| Current link | `mysql2i::$currObj` set by `mysql_connect` / `mysql_pconnect` |
| Charset | `utf8mb4` on successful connect (class + procedural wrapper) |
| Reuse | `mysql_connect` returns existing valid `$currObj` without opening a second link |

## `mysql_close`

Does not aggressively close the socket; returns `true` for compatibility with shutdown handlers. Connection ends with the PHP request.

## Errors and links

| Function | Behavior |
|----------|----------|
| `mysql_errno` / `mysql_error` | Resolve link via `resolveMysqliLink`; safe defaults when no link |
| `mysql_query` | Fails with warning + `error_log` when link invalid |
| `mysql_is_valid_link` | Accepts `mysqli` with `host_info` or legacy `resource` |

## Extensions (beyond classic mysql)

| API | Purpose |
|-----|---------|
| `mysql_multi_query_script($link, $script)` | Multi-statement scripts via `mysqli_multi_query` |
| `mysql2i::resolveMysqliLink` | Normalize link for error helpers |

## Deprecations (func layer)

- `mysql_escape_string` → `E_USER_DEPRECATED`
- `mysql_pconnect` → `E_USER_DEPRECATED`

## Guards

- Class: `if (!class_exists('mysql2i', false))`
- Functions: `if (!function_exists('mysql_affected_rows'))`

## Out of scope for this package

- PDO shim (separate project / Webcred `mysql2pdo`)
- Prepared statements wrapper
- Connection config from env files

When adding a **new** public `mysql_*` shim, update [../../docs/API.md](../../docs/API.md) and this file in the same change.
