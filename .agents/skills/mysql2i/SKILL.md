---
name: mysql2i
description: Default procedure for alvesbeto/mysql2i. Strict scope; shim changes only in src/; read shim-behavior before editing.
---

# mysql2i

Map: [../../index.md](../../index.md).

## Scope

- Change **only** files the user named or the error implicates (usually `src/mysql2i.*`).
- Do not edit `vendor/`, consumer apps, or Webcred from this repo unless explicitly requested.

## Before coding

1. [specs/shim-behavior.md](../../specs/shim-behavior.md) if touching `src/`.
2. [rules/php.md](../../rules/php.md) for PHP rules.
3. Grep for an existing method before adding a shim.

## Shim rules

- Business apps call **`mysql_*` only**; this package owns **`mysqli`** inside `src/`.
- Keep guards (`class_exists` / `function_exists`) intact.
- New public surface → update `docs/API.md`, `specs/shim-behavior.md`, `CHANGELOG.md`.

## Progress

```
Task Progress:
- [ ] 1. Identify strict scope (file + lines)
- [ ] 2. Open index.md → one row only
- [ ] 3. If ambiguous or breaking → sdd skill first; stop after PRD approval
- [ ] 4. Surgical edit in src/
- [ ] 5. runbooks/validate.md
- [ ] 6. Update spec/docs if behavior changed; no git write
```

## When to escalate to SDD

- New module, PDO twin, or semver-major behavior change.
- Multi-file release (CI, Packagist, docs, src) with unclear order.

Use [../sdd/SKILL.md](../sdd/SKILL.md).
