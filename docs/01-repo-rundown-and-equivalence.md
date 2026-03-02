# Repo Rundown and Equivalence

## Objective

Assess whether directories in this workspace represent the same core plugin lineage, and quantify what is shared vs diverged.

This file originally focused on `source/` vs `build/`; it now includes context from `downloaded/` and `repo/`.

## Snapshot: What Each Directory Contains

### `build/`

- WordPress plugin entrypoint at `build/super-logistics.php` (version `2.0.3`).
- Backend PHP source under `build/src/` with namespaced and segmented architecture:
  - `Setup/Core`, `Setup/DB`, `Setup/Routing`, `Setup/WP`
  - API areas for `Entity`, `Transaction`, `User`, `Report`, `PDF` (labels/docs/reports)
- Bundled frontend assets only under `build/view/dist/` (hashed filenames).
- Vendor dependencies included (`vendor/`).

### `source/`

- WordPress plugin entrypoint at `source/super-logistics.php` (version `2.0.0`).
- Backend PHP source under `source/src/` with older flatter setup layout (`setup/*`).
- Vue source code present under `source/view/` (components, router, store), plus non-hashed dist output.
- JS toolchain present (`package.json`, Vite config, tests).

## Quantified Backend Overlap (PHP `src/`)

From direct file/hash and similarity checks:

- `source/src`: **40** PHP files
- `build/src`: **45** PHP files
- Same relative path and byte-identical: **3**
- Same relative path but changed: **10**
- Source-only relative paths: **27**
- Build-only relative paths: **32**
- Identical files relocated (same content, new path casing/folder): **4**

### Strong Evidence of Shared Origin

- Same plugin identity and bootstrap pattern:
  - `super-logistics.php` in both trees wires `CustomRoles`, `FrontendManager`, `ScriptManager`, DB setup, and route declaration.
- Several setup classes are effectively the same code with namespace/path refactor:
  - `source/src/setup/CustomRoles.php` -> `build/src/Setup/WP/CustomRoles.php`
  - `source/src/setup/FrontendManager.php` -> `build/src/Setup/WP/FrontendManager.php`
  - `source/src/setup/db/ORM.php` -> `build/src/Setup/DB/ORM.php`
  - `source/src/setup/exceptions/*` -> `build/src/Setup/Exceptions/*`
- Common domain model themes remain: entities, shows, transactions, user-role-driven access, PDF/report generation.

### Strong Evidence of Divergence

- Large API/controller rewrites, not cosmetic renames.
- Schema and model relationship changes (not just code style).
- Reporting/label/document generators are substantially reorganized.
- Frontend packaging conventions differ (hashed modern output in `build` vs fixed output naming in `source` config).

## Notable Example Comparisons

### Example A: Route configuration evolved, not merely moved

- `source/src/setup/routing/routes.php`: array config with permissive callbacks (`__return_true` common), route names like `getTrailerManifest`.
- `build/src/Setup/Routing/RouteConfig.php`: class-based route construction, explicit permission callbacks (`isLoggedIn`, `isInternalAdmin`, etc.), changed endpoint methods and action names (`printTrailerManifest`, `printPalletManifest`, logout/status routes).

### Example B: Transaction transformation changed deeply

- `source/src/API/Transaction/Transformers/TransactionTransformer.php`: older relation-heavy fields (`client_id`, `shipper_id`, `receiver`, `pallet_no`, etc.).
- `build/src/API/Transaction/Transformers/TransactionTransformer.php`: modernized flattened transaction payload and computed fields (`arrival_status`, `nice_*`, guard-protected billable weight logic).

### Example C: Setup scripts shifted to bundle pattern in build

- `source/src/setup/ScriptManager.php`: enqueues `view/dist/bundle.js`, limited CSS globbing.
- `build/src/Setup/WP/ScriptManager.php`: globs `bundle*.js` and all css assets, supports hashed bundles and adds `loginURL` localization key.

## Confirmation Statement

`source/` appears to be a legitimate relative of `build/` (not a random unrelated codebase), but it is **not** an equivalent mirror. It reads like an older/alternate branch with partial overlap and significant backend divergence.

## New Context Addendum (`downloaded/` and `repo/`)

### `build/` vs `downloaded/`

- Extremely close in first-party runtime/plugin structure.
- Differences observed were minor in number and centered on formatting/packaging artifacts; no major architectural drift identified in reviewed paths.

### `repo/` vs `downloaded/`

- Very high overlap on shared first-party backend paths.
- Single critical code-level divergence identified in shared backend:
  - `src/API/Transaction/Transformers/TransactionTransformer.php`
  - `repo` version lacks the guard that prevents zero-increment infinite billable-weight loops.

### Practical lineage update

- `repo` appears to be the development base for the current architecture line.
- `downloaded` appears to be the deployed/package state of that line.
- `source` remains the most divergent and least suitable as primary baseline.
