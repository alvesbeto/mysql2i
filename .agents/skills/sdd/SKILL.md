---
name: sdd
description: Spec-driven workflow for ambiguous or contract-breaking mysql2i changes. Load only the reference for the active phase.
---

# SDD

Use when the change is **ambiguous**, **risky**, or **breaks consumers**. Routine fixes follow [../mysql2i/SKILL.md](../mysql2i/SKILL.md).

1. Read [references/workflow.md](references/workflow.md) for the current phase only.
2. Workspace: `.agents/sdd/<feature-kebab>/` — register in [../../sdd/index.md](../../sdd/index.md).
3. After merge, promote per [references/memory.md](references/memory.md).
4. Do **not** copy workspace PRDs into `specs/`; promote **behavior** to [../../specs/shim-behavior.md](../../specs/shim-behavior.md) or [../../specs/TECH.md](../../specs/TECH.md).

Templates: [references/templates.md](references/templates.md).
