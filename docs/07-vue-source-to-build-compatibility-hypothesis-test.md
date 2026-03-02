# Hypothesis Test: "Build Vue in `source/` and Drop into `build/`"

## Hypothesis Under Test

> Building Vue from `source/` and dropping the built assets into `build/` will work perfectly, because backend surface area did not change significantly.

## Verdict

**Rejected (very high confidence).**

`source` Vue artifacts are not contract-compatible with the current `build` backend in multiple high-impact ways: endpoint presence, HTTP verbs, parameter names, and request payload structure.

## Methodology

1. Extracted frontend API usage from `source/view` (`url:` patterns and request methods in mixins/utilities/components).
2. Compared to backend route declarations in `build/src/Setup/Routing/RouteConfig.php`.
3. Cross-checked critical controller request contracts in `build` (especially transaction/report/user/show flows).
4. Classified each mismatch by impact and confidence.

## Core Findings

## 1) Endpoint surface area changed materially (not just internals)

- `source` frontend references endpoints absent in `build` routes:
  - `entities`, `entities/code`, `entities/register`, `entities/codes`
  - `client`
  - `app-users`
  - `shows/relevant`
  - `transactions/search`
  - `transactions/labels`
  - `transactions/notes`
  - `transactions/external/qr`
  - `current-user`
- `build` route config exposes different or narrower API families:
  - `clients`, `shows`, `transactions`, `users`, `reports`
  - print-oriented transaction routes (`transactions/shipping`, `transactions/receiving/labels`, `transactions/receiving/docs`)

**Confidence: Very high**
**Justification:** direct string-level comparison between `source/view/*` URLs and `build/src/Setup/Routing/RouteConfig.php`.

## 2) Report endpoints use different HTTP method/parameter contracts

- `source` report mixin calls `GET`:
  - `/reports/trailer-manifest?trailerNum=...`
  - `/reports/pallet-manifest?palletNum=...`
  - `/reports/show-report?...`
  - `/reports/show-report-two?...`
- `build` routes define `POST` for manifest/report routes.
- `build` controller parameter names differ (`trailer_no`, `pallet_no`, `seal_no`), while `source` uses camel names (`trailerNum`, `palletNum`).
- `show-report-two` is not exposed in `build` routes.

**Confidence: Very high**
**Justification:** exact route declarations and controller param reads in `build` vs exact URL construction in `source` report mixins.

## 3) Transaction create/update payloads are structurally incompatible

- `source` frontend sends transaction payload as nested form field:
  - key `transaction` containing JSON, plus optional `image`.
- `build` `TransactionController::create` expects flat required params (`shipper`, `exhibitor`, `show_id`, `zone_id`, piece counts, totals, etc.) at top level.
- Same issue for update path: source-style body schema does not match build validation/field extraction.

**Confidence: Very high**
**Justification:** direct comparison of `source/view/components/data-input/mixin.js` request composition vs `build/src/API/Transaction/Controllers/TransactionController.php` required parameter checks and param usage.

## 4) Some similar-looking endpoints still mismatch in details

- `users/current` exists in both worlds (positive overlap), but:
  - other user-fetch paths in source include non-existent aliases (`current-user`, `app-users`) and older role filtering assumptions.
- `DELETE /transactions/${id}` pattern used by source is not explicitly declared as path-param route in build route config (`DELETE transactions` with request param pattern).
- `shows/` exists conceptually, but source forms/field names are camelCase-era (`dateStart`, etc.) whereas build controller expects snake_case (`date_start`, etc.) and different supporting fields/behavior.

**Confidence: High**
**Justification:** route config + request construction + controller parameter extraction are inconsistent across these flows.

## 5) Permission model drift increases runtime failure risk

- `build` routes enforce permission callbacks (`isAdmin`, `isInternalAdmin`, `isLoggedIn`, etc.).
- `source` expects broader accessibility patterns on some endpoints.
- Even when endpoint names overlap, role restrictions may block previously allowed calls.

**Confidence: High**
**Justification:** explicit permission callbacks in build route config and known role-gated controller behavior.

## Compatibility Matrix (Source Frontend -> Build Backend)

- `users/current` -> **Likely works** (method/path aligned).
- `users` -> **Conditionally works** (admin-gated in build).
- `transactions` basic GET/POST -> **Partially/likely fails** due to body schema mismatch for POST.
- `transactions/update` -> **Likely fails** due to payload contract mismatch.
- `reports/trailer-manifest` -> **Fails** (GET vs POST; `trailerNum` vs `trailer_no`).
- `reports/pallet-manifest` -> **Fails** (GET vs POST; `palletNum` vs `pallet_no`).
- `reports/show-report` -> **Likely fails** (verb/contract drift).
- `reports/show-report-two` -> **Fails** (not exposed by build routes).
- `transactions/labels` -> **Fails** (not in build routes).
- `transactions/notes` -> **Fails** (not in build routes).
- `transactions/search` -> **Fails** (not in build routes).
- `transactions/external/qr` -> **Fails** (not in build routes).
- `entities*` family -> **Fails** (build uses split `clients`/`shows`, no matching legacy endpoints).
- `client` -> **Fails** (no route in build config).
- `shows/relevant` -> **Fails** (no route in build config).

## Confidence Summary

- **Overall conclusion confidence: 0.95 / 1.00 (very high).**
- Primary evidence is first-order contract mismatch (route/method/param/body), not subjective architecture inference.
- Residual uncertainty (~0.05) exists only for hidden compatibility shims that might exist outside inspected code; no evidence of such shims was found in route declarations.

## Bottom-Line Truth Statement

Dropping a Vue build from `source/` into `build/` is **very unlikely** to work perfectly and is likely to fail quickly on API calls. The incompatibilities are not merely "under the hood"; they are observable at the network contract boundary.

## New Context Addendum (`repo/` and `downloaded/`)

This hypothesis result remains unchanged for `source/`.

However, after integrating `repo/`:

- `repo` frontend API usage is much more aligned with `build/downloaded` backend routes:
  - `users/current`, `users/logout`
  - `transactions/shipping`
  - `transactions/receiving/labels`
  - `transactions/receiving/docs`
  - `reports/trailer-manifest` and `reports/pallet-manifest` via POST
- This strongly suggests the intended pairing is:
  - `repo` frontend source -> packaged/deployed backend line (`build`/`downloaded`)

So:

- **`source` -> `build`** remains high-risk incompatible.
- **`repo` -> `build/downloaded`** appears to be the actively compatible direction.
