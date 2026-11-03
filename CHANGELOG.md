# Changelog

## [1.0.6] - 2026-11-03

### Documentation

- Document full parity with the [PHP mysql extension reference](https://www.php.net/manual/en/ref.mysql.php) (**48** functions) plus `mysql_multi_query_script`; corrected agent registry counts (previously **44**).

## [1.0.5] - 2026-11-03

### Removed

- `mysql_is_result()` and `mysql_free_result_if_needed()` — not part of the historical `mysql_*` API; use truthy checks and `mysql_free_result()` (no-op on invalid results in this shim).

## [1.0.4] - 2026-11-03

### Removed

- `mysql_is_valid_link()` — not part of the historical `mysql_*` API; consumers should use `$link instanceof mysqli` and app bootstrap for connection ownership.

## [1.0.3] - 2026-11-03

### Added

- `mysql_is_result()` and `mysql_free_result_if_needed()` — common PHP 8 migration helpers (no longer need app-local copies).

## [1.0.2] - 2026-11-03

### Changed

- **Breaking for apps that relied on it:** removed automatic `utf8mb4` on `mysql_connect` / `mysql_pconnect`. The shim only delegates to `mysqli_connect`; charset is the consumer’s responsibility (`mysql_set_charset`, `SET NAMES`, etc.), matching the original `mysql_*` contract.

## [1.0.1] - 2026-11-03

### Fixed

- `mysql_select_db`: when a valid `$link` is passed, set `mysql2i::$currObj` so implicit `mysql_*` calls match legacy shims (e.g. lesha888/mysql2i).

## [1.0.0] - 2026-11-03

### Added

- Composer package `alvesbeto/mysql2i` extracted from the Webcred adapter.
- Autoload: classmap (`mysql2i`) + files (`mysql_*` functions).
- Documentation: README and `docs/API.md`.
- Requirements: PHP ^8.0, ext-mysqli.

### Notes

- Behavior aligned with Webcred `classes/config/mysql2i.*` at extraction time.
- Added `.agents/` SDD + OKF layout for agent-efficient context (2026-11-03).
- Future versions should record breaking changes here if `mysql_close` semantics or escape fallbacks change.
