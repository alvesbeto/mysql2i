---
title: "mysqli only in src"
description: "ROUTING: Read when adding database access or splitting packages."
tags: [mysqli, adr]
status: accepted
date: 2026-11-03
---

# ADR-0004 — mysqli only under `src/`

## Context

Consumers must keep calling `mysql_*`. Direct `mysqli_*` in application code breaks the shim boundary.

## Decision

All `mysqli` / `mysqli_result` usage stays in `src/mysql2i.class.php` and `src/mysql2i.func.php`.

## Consequences

Reviews grep `mysqli` outside `src/` as a violation. New capabilities extend `mysql2i` static methods and global shims.
