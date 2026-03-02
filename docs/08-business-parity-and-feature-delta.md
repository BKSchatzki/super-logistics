# Business Parity and Feature Delta

## Question

Do these contexts fundamentally accomplish the same business use, and is the modern line mainly a refactor of `source/` or a functional change?

Contexts considered in this updated version:

- `source/`
- `build/`
- `downloaded/` (live package)
- `repo/` (developer repository)

## Executive Answer

- **Business-use parity:** **Yes, broadly** across all contexts. They target the same logistics workflow domain.
- **Implementation parity:** **No**. The `repo/build/downloaded` line is not just a refactor of `source`; it has meaningful contract and behavior changes.
- **Best characterization:** current line is **refactor-plus-product-evolution** relative to `source`.

## Confidence

- **Business parity conclusion:** High confidence (0.90)
- **“Not just refactor” conclusion:** Very high confidence (0.95)
- **Current-line cohesion (`repo/build/downloaded`) conclusion:** Very high confidence (0.95)

Confidence is high because this is supported by direct comparison of entrypoints, route declarations, controllers, schema setup, and frontend API usage.

## What Is Fundamentally the Same (Business Intent)

Both trees clearly support:

- WordPress-hosted logistics operations via `super-logistics` plugin namespace.
- Core actor/entity model:
  - clients, shows, users, transactions.
- Operational transaction workflows:
  - create/update/query logistics records tied to show/client context.
- Role-driven app behavior:
  - internal and client role types appear in both.
- Print/document outputs:
  - manifest/label style reporting remains central in both.

Interpretation: both are solving the same business problem category (event logistics management and operational documentation).

## Where It Is More Than a Refactor (Feature/Behavior Delta)

## 1) API Surface and Contracts Changed

- Endpoint families and method contracts diverge materially (not merely renamed internals).
- `build` route model is class-based with permission callbacks and different verbs/params in several places.
- `source` frontend calls multiple legacy endpoints not present in `build` routes (`entities/*`, `transactions/search`, `transactions/notes`, etc.).

This is a product-surface change, not just a refactor.

## 2) Data Lifecycle Semantics Expanded in `build`

- `build` introduces stronger active/trashed lifecycle patterns across entities/users/transactions.
- `build` adds `sl_user_status` and user restore/inactive/active flows.
- `source` has simpler/older patterns, including direct-delete style handling.

This is a business behavior change (governance/auditability), not only code organization.

## 3) Reporting/Document Flows Were Reworked

- `build` splits PDF generation into clearer domains (`labels`, `docs`, `reports`), and adds receiving docs/AW label pathways.
- `source` includes show-report endpoints (`getShowReport`, `getShowReportTwo`) that are not equivalently implemented in current `build` controller despite route intent.

This indicates mixed evolution: some capabilities were added/improved, while at least one prior capability appears reduced or in transition.

## 4) Transaction Semantics and Safety Logic Changed

- `build` includes guarded billable-weight logic preventing zero-increment infinite loop behavior.
- Transaction transformer/controller payload semantics differ substantially from `source`.

This is both a correctness fix and contract evolution.

## Refactor vs Feature Change Classification

## Pure/mostly refactor-style changes

- Setup namespace reorganization (`setup/*` -> `Setup/Core|DB|Routing|WP`).
- Utility class segmentation and modernization.
- Dist asset handling improvements (hash-friendly script/style loading).

## Feature modifications/additions

- Permission enforcement model centralization.
- User status lifecycle management and restore/reactivate flows.
- Show-place model and related show management expansion.
- Print flow restructuring (`shipping`, `receiving/labels`, `receiving/docs`).
- Billable-weight safety guard in active transformation path.

## Potential regressions/unfinished migration indicators

- `build` route config includes `reports/show-report`, but `ReportsController` currently exposes only trailer/pallet methods in inspected code.
- Legacy source frontend flows depend on routes not present in build config.

## Practical Conclusion for Future Decision-Making

If the decision is “treat `build` as just refactored `source`,” that would be misleading.
If the decision is “same business product family, but evolved with contract and behavior changes,” that aligns with evidence.

So:

- **Same core business use:** yes.
- **Just refactor:** no.
- **Feature set changed:** yes, in both additive and compatibility-breaking ways.

## New Context Addendum: `repo` and `downloaded`

- `repo` and `downloaded` are highly aligned in shared first-party backend/runtime paths.
- `downloaded` behaves as release/package expression of the modern architecture line.
- One critical internal inconsistency remains:
  - infinite-loop guard in transaction billable-weight transformation exists in `build/downloaded` but not in `repo`.

Interpretation:

- Feature-level direction is coherent in the modern line.
- A hotfix drift likely exists between development and release/package snapshots.
