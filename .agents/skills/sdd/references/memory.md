# Memory promotion

| What happened | Write to | Do not write |
|---------------|----------|--------------|
| Any line changed | Git (maintainer) | — |
| Notable delivery | [../../knowledge/log.md](../../knowledge/log.md) | Secrets, full diffs |
| Current behavior | [../../specs/shim-behavior.md](../../specs/shim-behavior.md) or [../../specs/TECH.md](../../specs/TECH.md) | PRD drafts |
| Repeatable decision | [../../adrs/](../../adrs/index.md) | Trial-and-error narrative |
| Non-obvious cross-link | [../../knowledge/concepts/](../../knowledge/index.md) | API duplicates |

Log entry format: `## YYYY-MM-DD: summary` at top of `log.md`.

Agent does not run git write ([ADR-0002](../../adrs/ADR-0002-agent-no-git-write.md)).

OKF bundle: [../../knowledge/index.md](../../knowledge/index.md). Root [../../index.md](../../index.md) declares `okf_version` ([ADR-0003](../../adrs/ADR-0003-okf-root-index.md)).
