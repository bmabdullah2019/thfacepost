---
name: systematic-debugging
description: >-
  Systematic 4-Phase Bug Diagnosis, Root-Cause Analysis & Fix Verification.
  Activate when troubleshooting runtime crashes, network failures, unexpected state mutations, or performance bottlenecks.
---

# Systematic Debugging Protocol

## 4-Phase Diagnostic Method

### 1. Replicate & Capture
- Identify exact conditions, input payloads, environment states, and error traces.
- Run a minimal reproducer script or inspect runtime logs directly.

### 2. Isolate & Hypothesize
- Narrow down the fault domain by binary-searching execution paths or inserting logging probes.
- Formulate testable hypotheses (e.g. CORS preflight failure, unhandled promise rejection, null pointer).

### 3. Surgical Fix
- Fix the fundamental root cause at the deepest appropriate layer.
- Preserve existing logic and add defensive bounds checking against boundary conditions.

### 4. Regression & Side-Effect Validation
- Re-run the reproducer to prove the bug is resolved.
- Execute the broader test suite or end-to-end flow to ensure zero collateral damage.
