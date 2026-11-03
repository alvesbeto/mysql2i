# Changelog

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
