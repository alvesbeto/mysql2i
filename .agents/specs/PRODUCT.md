---
title: "Product — current behavior"
description: "ROUTING: Read to learn what the library guarantees today. Open shim-behavior before coding. Not a code-style guide."
tags: [product, behavior]
---

# Product — current behavior

Composer package **alvesbeto/mysql2i**: legacy **`mysql_*`** API for PHP 8+ applications that have not migrated to PDO/mysqli/prepared statements yet.

## Consumers

| Consumer | Integration |
|----------|-------------|
| Legacy PHP apps | `composer require alvesbeto/mysql2i` + `vendor/autoload.php` |
| Webcred (optional) | Path repo; replaces local `classes/config/mysql2i.*` requires |

## Canonical behavior

Do not duplicate long prose here.

| Topic | Spec |
|-------|------|
| Shim invariants (charset, close, deprecations) | [shim-behavior.md](shim-behavior.md) |
| Function list (human reference) | [../../docs/API.md](../../docs/API.md) |
| Install and examples | [../../README.md](../../README.md) |

## Global acceptance

- Behavior change updates [shim-behavior.md](shim-behavior.md) (and [CHANGELOG.md](../../CHANGELOG.md)) in the same PR.
- Repeatable structural choice becomes an ADR.
- Studies without shipped code do not live under `specs/`.

Large or ambiguous features: [../skills/sdd/SKILL.md](../skills/sdd/SKILL.md).
