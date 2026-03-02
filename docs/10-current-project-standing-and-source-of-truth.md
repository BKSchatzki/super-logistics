# Current Project Standing and Source-of-Truth Decision

## Current Standing

With all four contexts considered (`source`, `build`, `downloaded`, `repo`):

- The project appears to have **two historical lines**:
  - legacy/divergent line: `source/`
  - current architecture line: `repo/`, `build/`, `downloaded/`
- The current architecture line is backend-segmented (`Setup/*`, `API/Entity|Transaction|User|PDF`) and aligns with modernized Vue app patterns in `repo/view`.

## Source-of-Truth Decision (Recommended)

- **Development source-of-truth:** `repo/`
- **Deployed behavior source-of-truth:** `downloaded/`
- **Packaging/reference source-of-truth:** `build/` (local package mirror/comparator)
- **Historical reference only:** `source/`

## Is `build/` just a refactor of `source/`?

No. With `repo` now in view, the picture is clearer:

- `repo/build/downloaded` represent a different, evolved contract line.
- `source` is not just pre-refactor formatting; it carries different endpoint contracts and data-flow expectations.

## Critical Reconciliation Gap

One important mismatch remains inside the “current line”:

- `repo` lacks the billable-weight increment guard in `TransactionTransformer`.
- `build` and `downloaded` include this guard.

Risk:

- If future releases are built strictly from current `repo` without patching, the infinite-loop vulnerability can be reintroduced.

## Confidence

- **Directory lineage assessment:** very high (0.95)
- **Source-of-truth recommendation:** high (0.90)
- **Critical guard mismatch finding:** very high (0.98)

Confidence is based on direct file-level comparison and route/controller inspection, not inferred metadata.

## Immediate Documentation-Level Conclusion

The safest operating model is:

1. Maintain and evolve from `repo`.
2. Regularly diff against `downloaded` before/after releases.
3. Ensure the billable-weight guard present in live/package line is represented in development line.
