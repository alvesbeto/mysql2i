# API reference — alvesbeto/mysql2i

All functions below are registered in the global scope after autoload (or after `require` of `mysql2i.func.php`). Implementation delegates to static methods on **`mysql2i`** in `src/mysql2i.class.php`.

**Coverage:** every function listed in the [PHP mysql extension manual](https://www.php.net/manual/en/ref.mysql.php) (**48** symbols) is implemented, plus **`mysql_multi_query_script`** (multi-statement scripts; not part of the original extension).

Signatures follow the historical PHP `mysql_*` documentation unless noted otherwise.

---

## Connection and database

### `mysql_connect($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)`

Opens a connection via `mysqli_connect`. Reuses `mysql2i::$currObj` when a valid connection already exists. Does **not** set charset; use `mysql_set_charset()` or your app’s SQL if needed.

**Returns:** `mysqli|false`

### `mysql_pconnect(...)`

Persistent connection (`p:` host prefix). Emits `E_USER_DEPRECATED`. Does **not** set charset automatically.

Unlike `mysql_connect`, **`mysql_pconnect` does not reuse an existing valid `$currObj`** — it always opens a new persistent connection and assigns `$currObj` (even when connect fails). The class implementation ignores `$new_link` and `$client_flags`. On connect failure the class may **`echo`** the error message in addition to logging.

### `mysql_select_db($dbname, $link = null)`

Selects a database with `mysqli_select_db`. When `$link` is a connected `mysqli`, updates `mysql2i::$currObj` so later calls without `$link` use that connection (legacy behavior). Returns `false` if the link or database name is empty.

### `mysql_close($link = null)`

Compatibility: **does not aggressively close** the connection; returns `true` in most cases.

### `mysql_ping($link = null)`

Checks the connection by running `SELECT 1` (not `mysqli_ping` alone).

---

## Queries

### Global `mysql_query()` vs `mysql2i::mysql_query()`

Both validate empty queries (`E_USER_WARNING`). When the connection is missing or invalid:

- **`mysql_query()`** (global): writes to `error_log` and emits **`trigger_error('mysql_query(): No MySQL connection')`**, then returns `false` without calling the class when the link check fails in the func layer.
- **`mysql2i::mysql_query()`**: writes to `error_log` only (no `trigger_error`); also requires `isset($link->host_info)` on the `mysqli` instance.

The global function resolves `$link === null` to `mysql2i::$currObj`, validates, then calls the class with that link.

### `mysql_query($query, $link = null)`

Executes SQL via `mysqli_query`. See **Global vs class** above for warning differences.

### `mysql_unbuffered_query($query, $link = null)`

`mysqli_query` with `MYSQLI_USE_RESULT`. The global function is a thin delegate. The class uses `$currObj` when `$link` is empty but performs **no** empty-query or connection checks (unlike `mysql_query`). Invalid links behave like raw mysqli.

### `mysql_db_query($database, $query, $link = null)`

Selects `$database` and runs `$query`.

### `mysql_multi_query_script($link, $script)`

**Package extension:** runs a multi-statement script, frees intermediate result sets, returns `false` on `mysqli` error.

---

## Result sets

| Function | Notes |
|----------|--------|
| `mysql_fetch_array($result, $resulttype = MYSQLI_BOTH)` | `null` becomes `false` |
| `mysql_fetch_assoc($result)` | same |
| `mysql_fetch_row($result)` | validates `mysqli_result` in the func layer |
| `mysql_fetch_object($result, $class_name = 'stdClass', $params = [])` | |
| `mysql_fetch_field($result, $field_offset = null)` | |
| `mysql_fetch_lengths($result)` | |
| `mysql_num_rows($result)` | |
| `mysql_num_fields($result)` | |
| `mysql_data_seek($result, $offset)` | |
| `mysql_free_result($result)` | safe no-op when result is invalid |
| `mysql_result($result, $row, $field = null)` | fetch by row index or column name |

---

## Field metadata

| Function |
|--------|
| `mysql_field_name($result, $field_offset)` |
| `mysql_field_type($result, $field_offset)` |
| `mysql_field_len($result, $field_offset)` |
| `mysql_field_flags($result, $field_offset)` |
| `mysql_field_table($result, $field_offset)` |
| `mysql_field_seek($result, $fieldnr)` |

---

## Errors and server info

| Function | Typical return |
|----------|----------------|
| `mysql_errno($link = null)` | `mysqli` error code; `0` without a link |
| `mysql_error($link = null)` | string; `''` without a link |
| `mysql_affected_rows($link = null)` | |
| `mysql_insert_id($link = null)` | |
| `mysql_info($link = null)` | |
| `mysql_stat($link = null)` | |
| `mysql_get_client_info()` | |
| `mysql_get_host_info($link = null)` | |
| `mysql_get_proto_info($link = null)` | |
| `mysql_get_server_info($link = null)` | |
| `mysql_client_encoding($link = null)` | |

---

## Escaping and charset

### `mysql_real_escape_string($escapestr, $link = null)`

Preferred for escaping with an active connection.

### `mysql_escape_string($escapestr)`

**Deprecated** — emits `E_USER_DEPRECATED` in the func layer.

### `mysql_set_charset($charset, $link = null)`

---

## Catalog / administration (legacy)

| Function |
|--------|
| `mysql_create_db($database_name, $link = null)` |
| `mysql_drop_db($database, $link = null)` |
| `mysql_list_dbs($link = null)` → `SHOW DATABASES` |
| `mysql_list_tables($database, $link)` |
| `mysql_list_fields($database_name, $table_name, $link = null)` → `SHOW COLUMNS` |
| `mysql_list_processes($link = null)` → returns `mysqli_thread_id` |
| `mysql_db_name($result, $row, $field = null)` |
| `mysql_tablename($result, $row, $field = null)` |
| `mysql_thread_id($link = null)` |

---

## Class `mysql2i` (advanced)

Public property:

- **`mysql2i::$currObj`** — current `mysqli` connection

Static methods mirror `mysql_*` functions (same names with an `mysql_` prefix on the method, e.g. `mysql2i::mysql_query`).

**Note:** Calling the class directly skips procedural-layer extras (for example the extra `trigger_error` on failed `mysql_query()`). Prefer globals for legacy apps that expect PHP 5 warning behavior.

Helper methods:

- **`mysql2i::resolveMysqliLink($link = null): ?mysqli`**
- **`mysql2i::mysql_multi_query_script($link, $script): bool`**

Direct class usage (no global function):

```php
$link = mysql2i::mysql_connect('127.0.0.1', 'user', 'pass');
mysql2i::mysql_select_db('app', $link);
$res = mysql2i::mysql_query('SELECT 1', $link);
```

---

## Manual load order

1. `src/mysql2i.class.php` (loads `Mysql2i\…` via built-in autoloader)
2. `src/mysql2i.func.php`

With Composer, only `vendor/autoload.php` is required.

---

## Namespace `Mysql2i`

| Type | Class | Role |
|------|-------|------|
| Session | `LinkContext` | Current connection + close guard (backs `mysql2i::$currObj`) |
| Helper | `ResolveMysqliLink` | Same as `mysql2i::resolveMysqliLink()` |
| Shim | `Shim\MysqlConnect`, `Shim\MysqlQuery`, … | One class per function; call `::invoke(...)` with the same parameters as the global |

Example:

```php
use Mysql2i\Shim\MysqlSelectDb;

MysqlSelectDb::invoke('my_database', $link);
```

Procedural globals still apply func-layer warnings and deprecations where documented below.

---
