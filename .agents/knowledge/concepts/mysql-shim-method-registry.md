---
type: Document
title: mysql shim method registry
description: Agent-facing catalog of every mysql_* shim and class helpers; line anchors in src/mysql2i.class.php and src/mysql2i.func.php.
generated:
  at: 2026-11-03T18:15:00-03:00
  by: process:agent
verified:
  - at: 2026-11-03T18:15:00-03:00
    by: process:agent
    note: Removed mysql_is_result / mysql_free_result_if_needed; line anchors from src/.
  - at: 2026-11-03T18:50:00-03:00
    by: process:agent
    note: Parity vs php.net ref.mysql.php — 48 official mysql_* + mysql_multi_query_script (49 globals); line anchors unchanged.
tags: [mysql-shim, api, behavior]
sources:
  - id: api-human
    title: docs/API.md
    path: docs/API.md
  - id: shim-contract
    title: specs/shim-behavior.md
    path: .agents/specs/shim-behavior.md
---

# mysql shim method registry

**49** global functions in `src/mysql2i.func.php`: all **48** symbols from the [PHP mysql extension reference](https://www.php.net/manual/en/ref.mysql.php), plus **`mysql_multi_query_script`** (Corbee extension). Each delegates to **`mysql2i::mysql_*`** in `src/mysql2i.class.php`. Class-only helpers: `resolveMysqliLink`, `$currObj` / `$isClosing`.

Human-long signatures: [docs/API.md](../../../docs/API.md). Invariants: [specs/shim-behavior.md](../../specs/shim-behavior.md).

**Legend — Func column:** `—` = thin delegate; `warn` = extra `trigger_error`; `dep` = `E_USER_DEPRECATED`; `guard` = pre-check before class.

---

## Class helpers (no global function)

| Symbol | Class L | mysqli / behavior |
|--------|---------|-------------------|
| `mysql2i::$currObj` | 12 | Implicit link for `empty($link)` call sites |
| `mysql2i::$isClosing` | 13 | Set during `mysql_close`; affects `mysql_real_escape_string` fallback |
| `mysql2i::resolveMysqliLink($link)` | 140 | Returns `$link` if `mysqli`, else `$currObj`, else `null`; used by `mysql_errno` / `mysql_error` |

---

## Connection and session

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_connect` | 57 | 58 | Reuses valid `$currObj`; else `mysqli_connect`; **no** implicit charset | — |
| `mysql_pconnect` | 498 | 285 | Always new `p:` connect; **no** reuse of valid `$currObj`; always `self::$currObj = $link`; `$new_link`/`$client_flags` unused in class; failure: `error_log` + **echo** in class | dep |
| `mysql_select_db` | 589 | 332 | `mysqli_select_db`; if explicit `$link` is connected, sets `$currObj`; empty `$dbname` → false | — |
| `mysql_close` | 37 | 45 | Does not close socket; `$isClosing` guard; func early `true` if link invalid | guard |
| `mysql_ping` | 511 | 291 | `SELECT 1` + free result, not `mysqli_ping` | — |

---

## Queries

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_query` | 534 | 297 | `mysqli_query`; func validates then calls class with resolved `$link`; class requires `host_info`; func adds **`trigger_error`** on bad link, class does not | warn |
| `mysql_unbuffered_query` | 652 | 361 | `mysqli_query(..., MYSQLI_USE_RESULT)`; `empty($link)` → `$currObj`; **no** empty-query or connect guards (unlike `mysql_query`) | — |
| `mysql_db_query` | 108 | 84 | `mysqli_select_db` then `mysqli_query` | — |
| `mysql_multi_query_script` | 160 | 108 | **Extension:** `mysqli_multi_query`, drain results, false on any errno | — |

---

## Result set navigation

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_fetch_array` | 215 | 119 | `mysqli_fetch_array`; `null` row → `false` | warn |
| `mysql_fetch_assoc` | 226 | 128 | `mysqli_fetch_assoc`; `null` → `false`; class skips warning if invalid result | warn |
| `mysql_fetch_row` | 268 | 154 | `mysqli_fetch_row`; `null` → `false` | warn |
| `mysql_fetch_object` | 253 | 149 | `mysqli_fetch_object` (+ optional ctor `$params`); `null` → `false` | — |
| `mysql_fetch_field` | 237 | 137 | Seeks with loop when `$field_offset` set, then `mysqli_fetch_field` | — |
| `mysql_fetch_lengths` | 248 | 143 | `mysqli_fetch_lengths` | — |
| `mysql_num_rows` | 489 | 276 | `mysqli_num_rows` | warn |
| `mysql_num_fields` | 480 | 267 | `mysqli_num_fields` | warn |
| `mysql_data_seek` | 86 | 69 | `mysqli_data_seek` | warn |
| `mysql_free_result` | 358 | 199 | Invalid result → `true` (no-op) | guard |
| `mysql_result` | 568 | 323 | Seek row; optional column name scan; else first column | warn |

---

## Field metadata

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_field_name` | 317 | 175 | `mysqli_fetch_field_direct` → `name` | — |
| `mysql_field_type` | 339 | 193 | Maps `MYSQLI_TYPE_*` constants to names | — |
| `mysql_field_len` | 310 | 169 | `mysqli_fetch_field_direct` → `length` | — |
| `mysql_field_flags` | 278 | 163 | Bit decode via `MYSQLI_*_FLAG` constants; lowercase string | — |
| `mysql_field_table` | 329 | 187 | `mysqli_fetch_field_direct` → `table` | — |
| `mysql_field_seek` | 324 | 181 | `mysqli_field_seek` | — |

---

## Errors, counts, server info

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_errno` | 183 | 96 | `resolveMysqliLink`; no link → `0` | — |
| `mysql_error` | 193 | 102 | `resolveMysqliLink`; no link → `''` | — |
| `mysql_affected_rows` | 15 | 33 | Requires valid `mysqli` with `host_info`; else `false` | — |
| `mysql_insert_id` | 412 | 238 | `mysqli_insert_id` | — |
| `mysql_info` | 403 | 232 | `mysqli_info` | — |
| `mysql_stat` | 621 | 343 | `mysqli_stat` | — |
| `mysql_get_client_info` | 369 | 208 | `mysqli_get_client_info($currObj)` | — |
| `mysql_get_host_info` | 376 | 214 | `mysqli_get_host_info` | — |
| `mysql_get_proto_info` | 385 | 220 | `mysqli_get_proto_info` | — |
| `mysql_get_server_info` | 394 | 226 | `mysqli_get_server_info` | — |
| `mysql_client_encoding` | 28 | 39 | `mysqli_character_set_name` | — |
| `mysql_thread_id` | 643 | 355 | `mysqli_thread_id` | — |

---

## Escaping and charset

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_real_escape_string` | 555 | 318 | `mysqli_real_escape_string`; if closing/invalid link → `addslashes` | — |
| `mysql_escape_string` | 203 | 113 | Uses `$currObj` or `addslashes`; insecure legacy | dep |
| `mysql_set_charset` | 612 | 337 | `mysqli_set_charset` | — |

---

## Catalog / administration (legacy)

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_create_db` | 70 | 63 | ``CREATE DATABASE `$name` `` via `mysqli_query`; bool from errno | — |
| `mysql_drop_db` | 119 | 90 | ``DROP DATABASE `$name` `` | — |
| `mysql_list_dbs` | 421 | 244 | `SHOW DATABASES` → `mysqli_result` or false | — |
| `mysql_list_tables` | 465 | 261 | ``SHOW TABLES FROM `$database` `` | — |
| `mysql_list_fields` | 441 | 249 | ``SHOW COLUMNS FROM `$table` `` — **`$database_name` unused in SQL** | — |
| `mysql_list_processes` | 456 | 255 | Returns **`mysqli_thread_id`** (scalar), not a result set | — |
| `mysql_db_name` | 95 | 78 | Seek row; first column of row | — |
| `mysql_tablename` | 630 | 349 | Same pattern as `mysql_db_name` | — |

---

## Global constants (`mysql2i.func.php`)

Defined when shim loads (map to `MYSQLI_*`): `MYSQL_BOTH`, `MYSQL_NUM`, `MYSQL_ASSOC`, `MYSQL_CLIENT_COMPRESS`, `MYSQL_CLIENT_SSL`, `MYSQL_CLIENT_INTERACTIVE`, `MYSQL_CLIENT_IGNORE_SPACE` (L11–L31).

---

## Maintenance

When adding or changing a symbol:

1. Edit `src/`.
2. Update this registry (class/func line numbers and notes).
3. Update [shim-behavior.md](../../specs/shim-behavior.md) if invariants change.
4. Update [docs/API.md](../../../docs/API.md) for human API.
5. Run [runbooks/validate.md](../../runbooks/validate.md).
6. Note in [log.md](../log.md) if the delivery is notable.

For ambiguous names, grep `src/` or use `path::symbol` in the registry tables.
