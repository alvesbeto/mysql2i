---
title: "PHP and shim editing"
description: "ROUTING: Read when changing src/mysql2i.class.php or src/mysql2i.func.php."
tags: [php, mysqli]
scope: "src/**"
---

# PHP rules

- PHP **8.0+** per `composer.json`.
- **All `mysqli` usage stays in `src/`** ([ADR-0004](../adrs/ADR-0004-mysqli-only-in-src.md)).
- Preserve global class name `mysql2i` and procedural `mysql_*` names for drop-in compatibility.
- Match existing style: no drive-by reformat; surgical diffs only.
- User-facing comments and docs in **English** ([ADR-0005](../adrs/ADR-0005-english-user-facing-docs.md)).
- Before adding a function, grep this repo — extend existing methods on `mysql2i` first.
- After behavior change, update [../specs/shim-behavior.md](../specs/shim-behavior.md) and [../../docs/API.md](../../docs/API.md).

Verify: [../runbooks/validate.md](../runbooks/validate.md).
