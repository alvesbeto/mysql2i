---
title: "English user-facing docs"
description: "ROUTING: Read when adding README, API, or source comments."
tags: [docs, adr]
status: accepted
date: 2026-11-03
---

# ADR-0005 — English user-facing documentation

## Decision

README, `docs/`, `CHANGELOG`, inline comments in `src/`, and agent routing text in `.agents/` use **English**.

Proper names (e.g. Beto Souza) may stay untranslated.

## Consequences

Portuguese prose belongs in consumer apps, not in this package's public surface.

Do not name proprietary downstream applications, their repositories, or internal directory layouts in README, `docs/`, `CHANGELOG`, or `.agents/` routing text.
