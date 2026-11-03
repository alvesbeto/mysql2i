---
title: "Agent no git write"
description: "ROUTING: Read before any commit/push suggestion."
tags: [git, adr]
status: accepted
date: 2026-11-03
---

# ADR-0002 — Agent does not run git write

## Decision

Agents must not run `git add`, `git commit`, or `git push`. Maintainers version changes.

Allowed: `git status`, `git diff`, `git log`.

## Consequences

Agents finish with a summary for the maintainer to commit.
