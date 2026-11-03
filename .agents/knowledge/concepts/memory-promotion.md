---
type: Policy
title: Memory promotion
description: Where to record facts after a change completes.
generated:
  at: 2026-11-03T15:20:00-03:00
  by: process:agent
---

# Memory promotion

Git holds the full diff. `.agents/` holds what you cannot get from one targeted read.

| Fact | Destination |
|------|-------------|
| Line changed | Git (maintainer) |
| Notable delivery | [log.md](../log.md) |
| Shim behavior | [specs/shim-behavior.md](../../specs/shim-behavior.md) |
| Stack / layout | [specs/TECH.md](../../specs/TECH.md) |
| Repeatable decision | [adrs/](../../adrs/index.md) |
| Non-obvious link | [concepts/](.) |

Procedure: [skills/sdd/references/memory.md](../../skills/sdd/references/memory.md).
