# SDD workflow

Workspace: `.agents/sdd/<feature-kebab>/`.

## Phase 0 — Capability map

Only if work spans packaging + API + release. Confirm [../../specs/CAPABILITY_MAP.md](../../specs/CAPABILITY_MAP.md) (usually unchanged).

## Phase 1 — Specify (STOP GATE)

Create or update `prd.md`.

Minimum: objective, scope, consumer impact, test/verify plan, boundaries.

**Rule:** Save the file. Summarize open questions. Ask human approval. **End the turn.** No plan or code in the same turn.

## Phase 2 — Plan

After approval, write `techspec.md`: components, order, contracts, out of scope.

Search this repo before new files; document reuse in `techspec.md`.

## Phase 3 — Tasks

Write `tasks.md`. Each task: acceptance + verify command. Max ~5 files per task.

## Phase 4 — Implement and sync

Implement. Update `specs/shim-behavior.md`, `docs/API.md`, `CHANGELOG.md`, ADRs as needed. See [memory.md](memory.md).

| Phase | File | Exit when |
|-------|------|-----------|
| PRD | `prd.md` | Behavior and success criteria explicit |
| TechSpec | `techspec.md` | Components and contracts explicit |
| Tasks | `tasks.md` | Each task has acceptance + verify |
| QA | `qa.md` | PRD criteria checked or gaps written |
| Review | `review.md` | Spec/code drift resolved |

Small one-file fixes **do not** open a workspace.

Optional IDs in prose: `CA-*`, `TU-*`. No file per ID.
