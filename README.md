# alvesbeto/mysql2i

Composer library that restores the **`mysql_*`** API (removed in PHP 7.0) on **PHP 8+**, backed by **`ext-mysqli`**.

It targets legacy applications that still call `mysql_connect`, `mysql_query`, `mysql_fetch_assoc`, and similar functions, so you can migrate to PHP 8 without rewriting all data-access code at once.

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP | `^8.0` (tested on 8.x) |
| Extension | `mysqli` (`ext-mysqli`) |
| MySQL / MariaDB | Whatever your `mysqli` driver supports |

The native PHP **`mysql`** extension no longer exists; this package is a **shim**, not a C extension reimplementation.

## Installation

### Via Composer (Packagist or VCS)

Once the package is published:

```bash
composer require alvesbeto/mysql2i
```

### Via path (monorepo or local development)

In the consuming project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../mysql2i",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "alvesbeto/mysql2i": "@dev"
    }
}
```

Then:

```bash
composer update alvesbeto/mysql2i
```

### Without Composer (not recommended)

```php
require_once '/path/to/mysql2i/src/mysql2i.class.php';
require_once '/path/to/mysql2i/src/mysql2i.func.php';
```

Order matters: **class file before the functions file**.

## Quick start

1. Load Composer autoload in your application bootstrap (before any `mysql_*` call):

```php
require_once __DIR__ . '/vendor/autoload.php';
```

2. Connect and use the legacy API as before:

```php
$link = mysql_connect('127.0.0.1', 'user', 'password');
if (!$link) {
    die('Connection failed: ' . mysql_error());
}

mysql_select_db('my_database', $link);
mysql_set_charset('utf8mb4', $link);

$result = mysql_query('SELECT id, name FROM customers LIMIT 10', $link);
while ($row = mysql_fetch_assoc($result)) {
    echo $row['name'] . "\n";
}
mysql_free_result($result);
```

### Global connection (`$currObj`)

The `mysql2i` class keeps the “current” connection in `mysql2i::$currObj`. Functions that omit the `$link` argument use that reference—the same behavior as classic `mysql_*` after `mysql_connect`.

```php
$link = mysql_connect($host, $user, $pass);
// mysql_query($sql) uses $link implicitly
mysql_query("SET time_zone = '-03:00'");
```

If your code stores the link in a variable (e.g. `$conMy01_0`), pass it explicitly: `mysql_query($sql, $conMy01_0)`.

## Legacy project integration (e.g. Webcred)

Replace local requires:

```php
// Before
require_once __DIR__ . '/classes/config/mysql2i.class.php';
require_once __DIR__ . '/classes/config/mysql2i.func.php';

// After (composer install)
require_once __DIR__ . '/vendor/autoload.php';
// mysql_* available via autoload "files" + classmap
```

Keep **mysqli only inside this package**; business code should keep calling `mysql_*` only.

## Composer autoload

| Mechanism | File | Role |
|-----------|------|------|
| `classmap` | `src/mysql2i.class.php` | Static `mysql2i` class |
| `files` | `src/mysql2i.func.php` | Global `mysql_*` functions and `MYSQL_*` constants |

Guards prevent redefinition if the shim is loaded twice:

- `class_exists('mysql2i', false)` on the class
- `function_exists('mysql_affected_rows')` on the functions

## Extended API (beyond classic mysql_*)

Useful on PHP 8 and for batch scripts:

| Function | Description |
|----------|-------------|
| `mysql_multi_query_script($link, $script)` | Runs multiple statements via `mysqli_multi_query`, draining results |
| `mysql_is_valid_link($link = null)` | Checks whether the link is a valid `mysqli` or legacy resource |
| `mysql2i::resolveMysqliLink($link)` | Normalizes a link to `mysqli` (internal / advanced) |

Full list and details: [docs/API.md](docs/API.md).

## Important behavior

### Charset

`mysql_connect` and `mysql_pconnect` set **`utf8mb4`** on the connection when it succeeds. The procedural `mysql_connect` wrapper also calls `set_charset('utf8mb4')` after connecting.

### `mysql_close`

For compatibility with long-running scripts and shutdown handlers, **`mysql_close` does not actually close the socket** in the current implementation: it returns `true` and avoids aggressive teardown. The connection ends when the PHP request finishes.

### Runtime deprecations

- `mysql_escape_string()` — triggers `E_USER_DEPRECATED`; prefer `mysql_real_escape_string()` or prepared statements.
- `mysql_pconnect()` — triggers `E_USER_DEPRECATED`.

### Security

This package **does not** replace prepared statements. `mysql_real_escape_string` delegates to `mysqli_real_escape_string`; with an invalid connection it falls back to `addslashes` (inherited adapter behavior).

For new code, use **PDO**, **mysqli prepared statements**, or an ORM—do not expand SQL string concatenation.

### Links and types

On PHP 8, links and results are **`mysqli`** and **`mysqli_result`** instances, not `resource`. Code that uses `is_resource($link)` should use `mysql_is_valid_link()` or `$link instanceof mysqli`.

## Defined constants

If not already defined, the shim defines:

`MYSQL_BOTH`, `MYSQL_NUM`, `MYSQL_ASSOC`, `MYSQL_CLIENT_COMPRESS`, `MYSQL_CLIENT_SSL`, `MYSQL_CLIENT_INTERACTIVE`, `MYSQL_CLIENT_IGNORE_SPACE` — mapped to the corresponding `MYSQLI_*` values.

## Package development

```bash
cd /path/to/mysql2i
composer validate --strict
composer dump-autoload -o
php -l src/mysql2i.class.php
php -l src/mysql2i.func.php
```

Minimal manual smoke test:

```bash
php -r "require 'vendor/autoload.php'; var_dump(function_exists('mysql_connect'), class_exists('mysql2i'));"
```

### Agent / SDD context

Maintainers and coding agents: start at [AGENTS.md](AGENTS.md) (map in [.agents/index.md](.agents/index.md)). Shim invariants for agents: [.agents/specs/shim-behavior.md](.agents/specs/shim-behavior.md).

## License

MIT — see [LICENSE](LICENSE).

## Credits

Implementation derived from the **mysql2i** adapter used in legacy Beto Souza projects. Partial credits for `mysql_field_flags` / `mysql_field_type` are noted in source comments (php.net / stackoverflow).
