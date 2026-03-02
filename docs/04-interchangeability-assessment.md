# Interchangeability Assessment

## Question

Are `source/` and `build/` fundamentally interchangeable, such that building Vue code from `source/` would make it effectively equivalent to `build/`?

## Short Answer

**No. They are not fundamentally interchangeable in their current state.**

Building `source/` frontend assets may produce a runnable SPA bundle, but it will not reconcile the major backend differences found in `build/src/`.

## Why Not Interchangeable

- **Backend divergence is substantial and independent of Vue build**
  - Route definitions differ (verbs, names, permission callbacks).
  - Controllers and models differ materially (not just formatting).
  - Database table contracts diverge, including status and show-place concerns.

- **`build/` includes backend-only behavior absent from `source/`**
  - User status table + lifecycle controls.
  - Permission mediation layer and route permission mapping.
  - Revised transaction transformation and billable-weight safety guard.
  - Expanded/segmented PDF generator architecture.

- **Frontend build conventions differ**
  - `source` Vite config emits fixed `bundle.js`/`bundle.css`.
  - `build` expects `bundle*.js` and globbed CSS assets in `ScriptManager`, indicating hash-friendly production pattern.
  - Even if this can be adjusted, it still does not solve backend contract mismatch.

## What "Partially Interchangeable" Could Mean

Some pieces are close enough to reuse or port:

- Setup exceptions/ORM/singleton and role/bootstrap scaffolding.
- Shared conceptual domain model (entities/shows/transactions/users/reports).
- WordPress shortcode mount and SPA concept.

But this is best treated as a **migration/refactor baseline**, not drop-in interchangeability.

## Recommended Operational Position

- Treat `build/` as the current production-oriented branch of behavior.
- Treat `source/` as an auxiliary/legacy/parallel source useful for:
  - recovering Vue component intent and UI affordances,
  - tracing historical report logic,
  - selectively porting features with explicit API/schema reconciliation.

## Confidence Statement

Confidence is high that these repos are related; confidence is also high that they are currently non-interchangeable without deliberate integration work across API, schema, and permission layers.

## New Context Addendum (`repo/` and `downloaded/`)

- The strict non-interchangeability finding applies to `source` <-> `build`.
- After adding new contexts:
  - `repo` is largely interchangeable with `build/downloaded` at architecture and route-contract level.
  - `downloaded` is effectively package/live expression of the same line as `build`.
- Important caveat:
  - `repo` currently differs in one critical transformer method (billable-weight guard), so it is not a perfect byte-equivalent to live/package behavior.
