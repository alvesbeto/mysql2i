## 2026-11-03: Remove `mysql_is_valid_link`

Dropped from `src/` (not historical `mysql_*`). Specs, registry (46 funcs), README, API, CHANGELOG 1.0.4 updated. Link checks: consumer bootstrap / `$link instanceof mysqli`.

## 2026-11-03: Remove Graphify from repo

Removed skill, rule, `.graphifyrc`, `graphify-out/`. Agent memory stays in SDD + OKF ([project-memory-model](concepts/project-memory-model.md), method registry, specs).

## 2026-11-03: Shim purity — no implicit charset

Removed `set_charset('utf8mb4')` from connect/pconnect in `src/`; docs and specs updated. See CHANGELOG 1.0.2.

## 2026-11-03: Human API notes (query / pconnect / unbuffered)

Same func-vs-class and connection semantics documented in [docs/API.md](../../docs/API.md) and [README.md](../../README.md).

## 2026-11-03: Query layer docs (func vs class)

Documented `mysql_query` dual validation, `mysql_pconnect` vs `mysql_connect`, and unguarded `mysql_unbuffered_query` in [shim-behavior.md](../specs/shim-behavior.md) and [method registry](concepts/mysql-shim-method-registry.md).

## 2026-11-03: Full project memory + Graphify

Graphify CLI was integrated then removed (2026-11-03). OKF bundle retained: [project-memory-model](concepts/project-memory-model.md), [mysql-shim-method-registry](concepts/mysql-shim-method-registry.md), promotion map, taxonomy, specs routers, capability map `agent-tooling`.

## 2026-11-03: Agent SDD / OKF layout

Added `.agents/` (index map, specs routers, shim-behavior contract, skills mysql2i + sdd, ADRs 0001–0005, runbooks, knowledge bundle). Symlinks `.cursor`, `.claude`, root `AGENTS.md`.
