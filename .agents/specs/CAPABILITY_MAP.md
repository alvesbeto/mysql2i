---
title: "Capability map"
description: "ROUTING: Read before a change that spans packaging + API + docs. Skip for a single-file bugfix inside src/."
tags: [capability]
---

# Capability map

Single-module library. No bounded contexts beyond the shim.

| Module id | Responsibility | Depends on |
|-----------|----------------|------------|
| `mysql-shim` | `mysql_*` compatibility over mysqli | — |

**Build order:** `mysql-shim` only.

**Dependency direction:** Consumers depend on this package. This package depends on PHP + ext-mysqli only. It must not depend on Webcred or application code.

Cross-cutting work (Packagist publish, semver major, new public API surface) → open SDD workspace under [../sdd/](../sdd/index.md).
