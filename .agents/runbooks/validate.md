# Validate package

Run from repo root:

```bash
composer validate --strict
composer dump-autoload -o
php -l src/mysql2i.class.php
php -l src/mysql2i.func.php
php -r "require 'vendor/autoload.php'; var_dump(function_exists('mysql_connect'), class_exists('mysql2i'));"
```

Expect: syntax OK and `bool(true) bool(true)`.

Before release (maintainer): tag, Packagist, smoke test in at least one integrating application if available.
