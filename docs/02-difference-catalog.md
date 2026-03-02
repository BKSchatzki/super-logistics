# Difference Catalog (`source/` vs `build/`)

This is an organized, near-exhaustive catalog of meaningful differences discovered in first-party plugin code.

## 1) Bootstrapping and Namespacing

- `source/super-logistics.php`
  - Version `2.0.0`
  - Uses `BigTB\SL\Setup\*` classes
  - Loads routes from `src/setup/routing/routes.php`
- `build/super-logistics.php`
  - Version `2.0.3`
  - Uses segmented namespaces `BigTB\SL\Setup\WP`, `Setup\DB`, `Setup\Routing`
  - Uses `new RouteConfig()` object for route definitions

## 2) Setup Layer Refactor

- `source/src/setup/*` flattened structure.
- `build/src/Setup/*` segmented into:
  - `Core` (controller/response/singleton)
  - `DB`
  - `Routing`
  - `WP`

Substantive behavior changes include:

- `RouteManager` in `build` converts non-`__return_true` permission strings into `Permissions` class callbacks.
- `ScriptManager` in `build` supports hashed bundle filenames (`bundle*.js`) and broad CSS loading.
- `Controller`/`ResponseManager` in `build` include standardized error sending and collection/single helpers.

## 3) Routing and Endpoint Surface

### `source` traits

- Route definitions in a plain PHP array.
- Broad permissive defaults (`__return_true`) appear frequently.
- Includes endpoints/features not present in `build` (for example package/item routes and show-report routes).

### `build` traits

- Class-driven route config.
- Explicit permission callback semantics (`isLoggedIn`, `isAdmin`, `isInternal`, etc.).
- Endpoint naming/verbs changed in places:
  - `getTrailerManifest` -> `printTrailerManifest` (POST)
  - `getPalletManifest` -> `printPalletManifest` (POST)
- Adds account lifecycle/status endpoints (user logout/restore/active/inactive patterns).

## 4) Data Model / Schema Differences

`TableManager` has major schema divergence:

- `build` adds `sl_user_status` and user active/trashed governance.
- `build` entities include `trashed` flag; `source` entities only `active`.
- `build` transactions table is expanded and explicit (shipper/exhibitor/carrier/tracking/zone_id/pieces/weights/status fields).
- `source` transaction table definition is much slimmer and appears inconsistent with its own controller/model usage.
- `build` introduces `sl_show_places` table for zones/booths and show-place lifecycle handling.

## 5) API Domain Reorganization

### Entity/Show domain

- `source` has separate `API/Show/*` area.
- `build` consolidates under `API/Entity/*` (`ShowController`, `ClientController`, `ShowPlaceController`) with richer show-place handling and status filters.

### User domain

- `build` user model uses prefixed capabilities key (`$wpdb->prefix . 'capabilities'`) and status relationship.
- `build` controller supports restore/inactivate/reactivate and client-scoped filtering.
- `source` user flow is simpler and directly deletes users (less soft-delete governance).

### Transaction domain

- `build` transaction controller validates required fields, manages image replacement logic, status transitions, and print flows for shipping/AW/docs.
- `source` controller still contains legacy patterns (`index`, `trash`, `search`, notes/update relations) and references older relation model assumptions.

## 6) PDF/Document/Report Generators

### `source`

- Legacy generators under `API/PDF/`:
  - `LabelGenerator`, `ExternalLabelGenerator`
  - `TrailerManifestGenerator`, `PalletManifestGenerator`
  - `ShowReportGenerator`, `ShowReportGeneratorTwo`
  - `SLPDF`

### `build`

- Segmented generators under:
  - `API/PDF/labels/*`
  - `API/PDF/docs/*`
  - `API/PDF/reports/*`
- Adds receiving docs and AW label generator structure in dedicated namespaces.
- Report generators are rewritten and not text-equivalent to `source` versions.
- `build` report controller imports show-report classes but currently only exposes trailer/pallet methods, indicating transitional leftovers.

## 7) Frontend Packaging and Build Output

- `source` includes Vue source tree, router, store, components, tests, Vite config.
- `source/vite.config.js` outputs fixed names (`bundle.js`, `bundle.css`).
- `build` contains dist-only assets with hashed filenames (`bundle*.js`, `main*.css`, primeicons asset).
- `build` does not include editable Vue source in-tree.

## 8) Low-Confidence Signals in `source/`

Examples suggesting `source/` is not a clean canonical handoff:

- Route import namespace mismatch in `source/src/setup/routing/routes.php`:
  - references `BigTB\SL\API\Package\Controllers\PackageController` while code path is `API/Item/Controllers/PackageController.php`.
- Several model/controller relationships appear internally inconsistent with table definitions (especially transactions/items/updates era mismatch).
- Older TODO-heavy controllers and different naming conventions suggest partial or stale branch state.

## 9) Practical Read

`build/` looks like a later refactor/hardening pass over core plugin concepts shared with `source/`, not a mere compiled artifact of `source`.
