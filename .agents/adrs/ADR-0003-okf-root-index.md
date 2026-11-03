---
title: "OKF on root index"
description: "ROUTING: Read when changing index.md or knowledge bundle."
tags: [okf, adr]
status: accepted
date: 2026-11-03
---

# ADR-0003 — OKF on root index

## Decision

- [index.md](../index.md) frontmatter includes `okf_version: "0.2"`.
- [knowledge/index.md](../knowledge/index.md) declares the same version.
- Sub-indexes (`rules/index.md`, `specs/index.md`, …) **no** OKF frontmatter.
- `specs/PRODUCT.md`, `TECH.md`, `CAPABILITY_MAP.md` are **routers** with `description` routing hints — they do not replace [shim-behavior.md](../specs/shim-behavior.md).

## Consequences

Agents load one map row; OKF concepts live under `knowledge/concepts/` when a fact is not in code or spec.
