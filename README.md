# alvesbeto/mysql2i

**Legacy `mysql_*` functions for PHP 8+, implemented on `ext-mysqli`.**

PHP removed the old `mysql` extension in 7.0. Many applications still call `mysql_connect`, `mysql_query`, `mysql_fetch_assoc`, and the rest. This Composer library registers those global functions again and delegates to **mysqli** under the hood, so you can run on PHP 8 without rewriting every query at once.

- **Repository:** [github.com/alvesbeto/mysql2i](https://github.com/alvesbeto/mysql2i)
- **API reference:** [docs/API.md](docs/API.md)

This is a **compatibility shim**, not a reimplementation of the C extension.

---

## Requirements

| Requirement | Notes |
|-------------|--------|
| PHP | `^8.0` (see `composer.json`) |
| Extension | `mysqli` (`ext-mysqli`, required) |
| Database server | Any MySQL or MariaDB version your `mysqli` driver supports |

You must install and enable `ext-mysqli` in `php.ini`. This package does not ship a database server or Docker setup.

---

## Installation

### From Packagist (recommended)

```bash
composer require alvesbeto/mysql2i
```

After the package is submitted to [Packagist](https://packagist.org/packages/alvesbeto/mysql2i), that is the normal way to install it. Prefer a tagged release when available, for example:

```bash
composer require alvesbeto/mysql2i:^1.0
```

**Not on Packagist yet?** Point Composer at the GitHub repository, then require `dev-main`:

```bash
composer config repositories.mysql2i vcs https://github.com/alvesbeto/mysql2i
composer require alvesbeto/mysql2i:dev-main
```

### From a local clone (path repository)

Use this when the package lives next to your app in a monorepo or you are hacking on mysql2i itself.

Add to the **consumer** project's `composer.json`:

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

Then run:

```bash
composer update alvesbeto/mysql2i
```

Adjust `url` to the relative or absolute path of this repository.

### Without Composer (discouraged)

Load the two source files **in this order**:

```php
require_once '/path/to/mysql2i/src/mysql2i.class.php';
require_once '/path/to/mysql2i/src/mysql2i.func.php';
```

---

## Quick start

1. Load autoload **before** any `mysql_*` call (typically in your bootstrap):

```php
require_once __DIR__ . '/vendor/autoload.php';
```

2. Use the API like PHP 5 did. Connection and result handles are **`mysqli`** and **`mysqli_result` objects**, not `resource` types:

```php
$link = mysql_connect('127.0.0.1', 'user', 'password');
if (!$link) {
    die('Connection failed: ' . mysql_error());
}

mysql_select_db('my_database', $link);

$result = mysql_query('SELECT id, name FROM customers LIMIT 10', $link);
while ($row = mysql_fetch_assoc($result)) {
    echo $row['name'] . "\n";
}
mysql_free_result($result);
```

Successful `mysql_connect` / `mysql_pconnect` already set the connection charset to **`utf8mb4`**. You only need `mysql_set_charset()` if you want something else.

### Default connection (`mysql2i::$currObj`)

After `mysql_connect`, many functions accept an optional `$link`. If you omit it, they use the last connection stored in `mysql2i::$currObj` (same idea as classic `mysql_*`):

```php
$link = mysql_connect($host, $user, $pass);
mysql_query("SET time_zone = '-03:00'"); // uses $link via $currObj
```

If your app keeps the link in a variable (for example `$conMy01_0`), pass it explicitly: `mysql_query($sql, $conMy01_0)`.

Calling `mysql_select_db($database, $link)` with an explicit connection also updates `$currObj`, so the next `mysql_query($sql)` without a link uses that same connection.

---

## Migrating from copied shim files

If you previously shipped copies of `mysql2i.class.php` and `mysql2i.func.php` inside the project (common in legacy codebases):

```php
// Before
require_once __DIR__ . '/classes/config/mysql2i.class.php';
require_once __DIR__ . '/classes/config/mysql2i.func.php';

// After Composer install
require_once __DIR__ . '/vendor/autoload.php';
```

Remove the old copies once autoload is in place to avoid loading the shim twice.

**Encapsulation:** only this package should call `mysqli_*` directly. Application code should keep using `mysql_*`.

---

## How Composer autoload works

| Mechanism | File | What it loads |
|-----------|------|----------------|
| `classmap` | `src/mysql2i.class.php` | Class `mysql2i` |
| `files` | `src/mysql2i.func.php` | Global `mysql_*` functions and `MYSQL_*` constants |

If the shim is included twice in one request, guards skip redefinition:

- Class: `class_exists('mysql2i', false)`
- Functions: `function_exists('mysql_affected_rows')` (first function in the block)

---

## Extensions beyond classic `mysql_*`

| Symbol | Purpose |
|--------|---------|
| `mysql_multi_query_script($link, $script)` | Run a multi-statement SQL script via `mysqli_multi_query`, draining result sets |
| `mysql_is_valid_link($link = null)` | Whether the link is a connected `mysqli` (or legacy `resource`) |
| `mysql2i::resolveMysqliLink($link)` | Normalize a link to `mysqli` (advanced / internal) |

See [docs/API.md](docs/API.md) for the full function list and return types.

---

## Behavior to know before production

### Connection handles and `is_resource()`

On PHP 8, `$link` and query results are objects. Replace `is_resource($link)` with `mysql_is_valid_link($link)` or `$link instanceof mysqli`.

### `mysql_close()`

For long-running scripts and shutdown handlers, **`mysql_close()` does not tear down the socket**. It usually returns `true` without aggressively closing. The connection ends when the PHP request finishes. Do not rely on it to free the connection mid-request.

### Deprecated helpers

| Function | What happens |
|----------|----------------|
| `mysql_escape_string()` | Emits `E_USER_DEPRECATED`; use `mysql_real_escape_string()` or prepared statements |
| `mysql_pconnect()` | Emits `E_USER_DEPRECATED`; still connects with a `p:` host prefix when used |

### Security

This shim **does not add prepared statements**. `mysql_real_escape_string()` uses `mysqli_real_escape_string()` when the link is valid; otherwise it falls back to `addslashes()` (legacy adapter behavior).

For new code, prefer **PDO**, **mysqli prepared statements**, or an ORM. Do not grow new SQL built from string concatenation.

### Constants

If not already defined, the shim defines `MYSQL_BOTH`, `MYSQL_NUM`, `MYSQL_ASSOC`, and the `MYSQL_CLIENT_*` flags, mapped from the matching `MYSQLI_*` values.

---

## Developing this package

From the repository root:

```bash
composer validate --strict
composer dump-autoload -o
php -l src/mysql2i.class.php
php -l src/mysql2i.func.php
php -r "require 'vendor/autoload.php'; var_dump(function_exists('mysql_connect'), class_exists('mysql2i'));"
```

Expected output: `bool(true) bool(true)`.

**Maintainers and coding agents:** start at [AGENTS.md](AGENTS.md) (index: [.agents/index.md](.agents/index.md)). Shim contract: [.agents/specs/shim-behavior.md](.agents/specs/shim-behavior.md).

---

## License

MIT — see [LICENSE](LICENSE).

## Credits

Implementation evolved from the **mysql2i** adapter used in legacy Beto Souza projects. Source comments credit contributors for parts of `mysql_field_flags` / `mysql_field_type` (php.net / Stack Overflow).
