---
type: Document
title: mysql shim method registry
description: Agent-facing catalog of every mysql_* shim and class helpers; line anchors in src/mysql2i.class.php and src/mysql2i.func.php.
generated:
  at: 2026-11-03T16:45:00-03:00
  by: process:agent
verified:
  - at: 2026-11-03T16:55:00-03:00
    by: process:agent
    note: mysql_query / mysql_pconnect / mysql_unbuffered_query func-vs-class notes.
    note: Cross-checked against src/mysql2i.class.php and src/mysql2i.func.php in working tree.
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

**47** global `mysql_*` functions in `src/mysql2i.func.php` delegate to **`mysql2i::mysql_*`** in `src/mysql2i.class.php`, plus **2** result helpers (`mysql_is_result`, `mysql_free_result_if_needed`). Two class-only helpers: `resolveMysqliLink`, `$currObj` / `$isClosing`.

Human-long signatures: [docs/API.md](../../../docs/API.md). Invariants: [specs/shim-behavior.md](../../specs/shim-behavior.md).

**Legend — Func column:** `—` = thin delegate; `warn` = extra `trigger_error`; `dep` = `E_USER_DEPRECATED`; `guard` = pre-check before class.

---

## Class helpers (no global function)

| Symbol | Class L | mysqli / behavior |
|--------|---------|-------------------|
| `mysql2i::$currObj` | 12 | Implicit link for `empty($link)` call sites |
| `mysql2i::$isClosing` | 13 | Set during `mysql_close`; affects `mysql_real_escape_string` fallback |
| `mysql2i::resolveMysqliLink($link)` | 144 | Returns `$link` if `mysqli`, else `$currObj`, else `null`; used by `mysql_errno` / `mysql_error` |

---

## Connection and session

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_connect` | 57 | 58 | Reuses valid `$currObj`; else `mysqli_connect`; **no** implicit charset | — |
| `mysql_pconnect` | 502 | 291 | Always new `p:` connect; **no** reuse of valid `$currObj`; always `self::$currObj = $link`; `$new_link`/`$client_flags` unused in class; failure: `error_log` + **echo** in class | dep |
| `mysql_select_db` | 598 | 338 | `mysqli_select_db`; if explicit `$link` is connected, sets `$currObj`; empty `$dbname` → false | — |
| `mysql_close` | 37 | 45 | Does not close socket; `$isClosing` guard; func early `true` if link invalid | guard |
| `mysql_ping` | 520 | 297 | `SELECT 1` + free result, not `mysqli_ping` | — |
| `mysql_is_valid_link` | 675 | 373 | `mysqli` with `host_info` and no `connect_errno`, or legacy `resource` | — |

---

## Queries

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_query` | 543 | 303 | `mysqli_query`; func validates then calls class with resolved `$link`; class requires `host_info`; func adds **`trigger_error`** on bad link, class does not | warn |
| `mysql_unbuffered_query` | 661 | 367 | `mysqli_query(..., MYSQLI_USE_RESULT)`; `empty($link)` → `$currObj`; **no** empty-query or connect guards (unlike `mysql_query`) | — |
| `mysql_db_query` | 112 | 90 | `mysqli_select_db` then `mysqli_query` | — |
| `mysql_multi_query_script` | 164 | 114 | **Extension:** `mysqli_multi_query`, drain results, false on any errno | — |

---

## Result set navigation

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_fetch_array` | 219 | 125 | `mysqli_fetch_array`; `null` row → `false` | warn |
| `mysql_fetch_assoc` | 230 | 134 | `mysqli_fetch_assoc`; `null` → `false`; class skips warning if invalid result | warn |
| `mysql_fetch_row` | 272 | 160 | `mysqli_fetch_row`; `null` → `false` | warn |
| `mysql_fetch_object` | 257 | 155 | `mysqli_fetch_object` (+ optional ctor `$params`); `null` → `false` | — |
| `mysql_fetch_field` | 241 | 143 | Seeks with loop when `$field_offset` set, then `mysqli_fetch_field` | — |
| `mysql_fetch_lengths` | 252 | 149 | `mysqli_fetch_lengths` | — |
| `mysql_num_rows` | 493 | 282 | `mysqli_num_rows` | warn |
| `mysql_num_fields` | 484 | 273 | `mysqli_num_fields` | warn |
| `mysql_data_seek` | 90 | 75 | `mysqli_data_seek` | warn |
| `mysql_free_result` | 362 | 205 | Invalid result → `true` (no-op) | guard |
| `mysql_is_result` | 682 | 372 | `mysqli_result` or legacy `resource` | — |
| `mysql_free_result_if_needed` | 694 | 377 | void; frees only when `mysql_is_result` | — |
| `mysql_result` | 577 | 329 | Seek row; optional column name scan; else first column | warn |

---

## Field metadata

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_field_name` | 321 | 181 | `mysqli_fetch_field_direct` → `name` | — |
| `mysql_field_type` | 343 | 199 | Maps `MYSQLI_TYPE_*` constants to names | — |
| `mysql_field_len` | 314 | 175 | `mysqli_fetch_field_direct` → `length` | — |
| `mysql_field_flags` | 282 | 169 | Bit decode via `MYSQLI_*_FLAG` constants; lowercase string | — |
| `mysql_field_table` | 333 | 193 | `mysqli_fetch_field_direct` → `table` | — |
| `mysql_field_seek` | 328 | 187 | `mysqli_field_seek` | — |

---

## Errors, counts, server info

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_errno` | 187 | 102 | `resolveMysqliLink`; no link → `0` | — |
| `mysql_error` | 197 | 108 | `resolveMysqliLink`; no link → `''` | — |
| `mysql_affected_rows` | 15 | 33 | Requires valid `mysqli` with `host_info`; else `false` | — |
| `mysql_insert_id` | 416 | 244 | `mysqli_insert_id` | — |
| `mysql_info` | 407 | 238 | `mysqli_info` | — |
| `mysql_stat` | 630 | 349 | `mysqli_stat` | — |
| `mysql_get_client_info` | 373 | 214 | `mysqli_get_client_info($currObj)` | — |
| `mysql_get_host_info` | 380 | 220 | `mysqli_get_host_info` | — |
| `mysql_get_proto_info` | 389 | 226 | `mysqli_get_proto_info` | — |
| `mysql_get_server_info` | 398 | 232 | `mysqli_get_server_info` | — |
| `mysql_client_encoding` | 28 | 39 | `mysqli_character_set_name` | — |
| `mysql_thread_id` | 652 | 361 | `mysqli_thread_id` | — |

---

## Escaping and charset

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_real_escape_string` | 564 | 324 | `mysqli_real_escape_string`; if closing/invalid link → `addslashes` | — |
| `mysql_escape_string` | 207 | 119 | Uses `$currObj` or `addslashes`; insecure legacy | dep |
| `mysql_set_charset` | 621 | 343 | `mysqli_set_charset` | — |

---

## Catalog / administration (legacy)

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_create_db` | 74 | 69 | ``CREATE DATABASE `$name` `` via `mysqli_query`; bool from errno | — |
| `mysql_drop_db` | 123 | 96 | ``DROP DATABASE `$name` `` | — |
| `mysql_list_dbs` | 425 | 250 | `SHOW DATABASES` → `mysqli_result` or false | — |
| `mysql_list_tables` | 469 | 267 | ``SHOW TABLES FROM `$database` `` | — |
| `mysql_list_fields` | 445 | 255 | ``SHOW COLUMNS FROM `$table` `` — **`$database_name` unused in SQL** | — |
| `mysql_list_processes` | 460 | 261 | Returns **`mysqli_thread_id`** (scalar), not a result set | — |
| `mysql_db_name` | 99 | 84 | Seek row; first column of row | — |
| `mysql_tablename` | 639 | 355 | Same pattern as `mysql_db_name` | — |

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
