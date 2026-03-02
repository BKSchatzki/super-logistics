# File-Level Mapping Appendix

This appendix gives a concrete file-level map for rapid future ingestion.

## A) Identical or Near-Identical Moves/Renames

- `source/src/setup/db/ORM.php` -> `build/src/Setup/DB/ORM.php` (identical)
- `source/src/setup/exceptions/ClassNotFound.php` -> `build/src/Setup/Exceptions/ClassNotFound.php` (identical)
- `source/src/setup/exceptions/InvalidRouteHandler.php` -> `build/src/Setup/Exceptions/InvalidRouteHandler.php` (identical)
- `source/src/setup/exceptions/UndefinedMethodCall.php` -> `build/src/Setup/Exceptions/UndefinedMethodCall.php` (identical)
- `source/src/setup/CustomRoles.php` -> `build/src/Setup/WP/CustomRoles.php` (very close)
- `source/src/setup/FrontendManager.php` -> `build/src/Setup/WP/FrontendManager.php` (very close)
- `source/src/setup/Singleton.php` -> `build/src/Setup/Core/Singleton.php` (very close)

## B) Same Relative Path but Meaningfully Changed

- `API/Entity/Controllers/EntityController.php`
- `API/Entity/Models/Entity.php`
- `API/Entity/Transformers/EntityTransformer.php`
- `API/Report/Controllers/ReportsController.php`
- `API/Transaction/Controllers/TransactionController.php`
- `API/Transaction/Models/Transaction.php`
- `API/Transaction/Transformers/TransactionTransformer.php`
- `API/User/Controllers/UserController.php`
- `API/User/Models/User.php`
- `API/User/Transformers/UserTransformer.php`

## C) Source-Only API Families (not present as equivalents in build)

- `API/Item/*` (controllers/models/transformers)
- `API/Show/*` family in standalone namespace
- `API/Update/Models/Update.php`
- Legacy `API/PDF/*` classes:
  - `ExternalLabelGenerator.php`
  - `LabelGenerator.php`
  - `PalletManifestGenerator.php`
  - `TrailerManifestGenerator.php`
  - `ShowReportGenerator.php`
  - `ShowReportGeneratorTwo.php`
  - `SLPDF.php`
- `setup/routing/routes.php` (array-style route file)

## D) Build-Only API Families (not present as equivalents in source)

- `API/Entity/Controllers/ClientController.php`
- `API/Entity/Controllers/ShowPlaceController.php`
- `API/Entity/Controllers/ShowController.php` (reworked location/behavior)
- `API/Entity/Models/ShowPlace.php`
- `API/User/Models/UserStatus.php`
- `API/PDF/docs/*` (receiver docs)
- `API/PDF/labels/*` (segmented label generators)
- `API/PDF/reports/*` (segmented report generators)
- `Setup/Routing/Permissions.php`
- `Setup/Routing/RouteConfig.php`

## E) Frontend Presence

- `source/view/`: full Vue source + router/store/components + tests + Vite config.
- `build/view/`: dist assets only (no editable Vue source present in tree).

## F) Guidance for Future Agents

- Do not assume same endpoint names or request payloads across directories.
- Check `build/src/Setup/Routing/RouteConfig.php` as canonical for current backend routes.
- Treat `source/view/` as useful UI intent/reference material rather than authoritative backend contract.

## G) New Context Mapping (`repo` and `downloaded`)

- `repo` contains full editable frontend source (`view/components`, composables, tests) plus modern backend layout.
- `downloaded` contains packaged runtime shape (compiled `view/dist`, backend src, vendor), matching live-style deployment.
- `repo` <-> `downloaded` shared backend files are almost fully identical, with one critical exception:
  - `src/API/Transaction/Transformers/TransactionTransformer.php`
  - guard present in `downloaded`, absent in `repo`.
