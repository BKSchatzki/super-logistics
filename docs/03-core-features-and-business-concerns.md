# Core Features and Business Concerns

This document captures the functional/business understanding of Super Logistics based on both repositories.

## Product Intent (Inferred)

Super Logistics is a WordPress-hosted logistics operations system for event/show freight handling. It supports intake, tracking, reporting, and operational document generation across internal staff and client-side users.

## Core Domain Objects

- **Entities** (`sl_entities`)
  - Multiplexed table for clients, shows, carriers (type-driven).
- **Shows** (`sl_shows`)
  - Show metadata plus billing attributes:
    - `min_carat_weight`
    - `carat_weight_inc`
    - date range, floor plan
- **Show places** (`sl_show_places` in `build`)
  - Zones/booths associated to shows.
- **Transactions** (`sl_transactions`)
  - Shipment/receiving records with piece counts, weight, freight type, pallet/trailer, remarks, and image path.
- **Users + status**
  - WordPress users with app roles and entity associations.
  - In `build`, user lifecycle control via `sl_user_status` (`active`, `trashed`).

## Core Features (User-Facing / Operational)

- **Transaction capture and maintenance**
  - Create/update inbound logistics records.
  - Track shipper/exhibitor/carrier/tracking/location details.
  - Track piece composition and total weight.

- **Role-aware access**
  - Roles include internal and client admin/employee classes.
  - Internal vs client role distinctions affect visibility and actions.

- **Show and client management**
  - Manage entities, show dates, floor plans, and associated place lists (zones/booths in `build`).

- **Document generation**
  - Shipping labels.
  - Advance warehouse labels.
  - Receiver documents (in `build`).
  - Pallet and trailer manifests.
  - Show-level reporting (present in `source`; less exposed in current `build` routes).

- **CSV export for operational reporting**
  - Receiver management supports client-side CSV export from the data table.
  - Export output is tied to currently selected/visible columns rather than a fixed export contract.
  - Tracking is included in the default selected export columns (Version A), while still remaining user-toggleable.

- **Frontend app in WordPress page**
  - Shortcode mounts SPA container `#super-logistics-app`.
  - Vue app routes include home/data entry, transactions, scanner, reports, users, clients, settings, public-facing screen (source tree evidence).

## Export Capability (Current State and Concern)

- **Current state**
  - CSV export is initiated from the Receivers view via PrimeVue table export.
  - Tracking data is present in transaction payloads, available as a selectable column, and now selected by default for export.
- **Business concern**
  - Client expectation is that tracking is practically available in normal export flow; this is now met in default behavior.
- **Operational implication**
  - A configurable UI export is useful for flexibility, but strict always-on tracking would still require a fixed export contract because users can deselect the field.

## POD / Media Lifecycle (Current State and Concern)

- **Current state**
  - Transaction flow already supports image capture/upload and persistence (`image_path`).
  - Image upload uses WordPress upload handling and image mime constraints.
  - API returns image path with transaction records.
  - POD print/view is now implemented via a dedicated backend PDF endpoint (`transactions/receiving/pod`) and receiver-details action.
- **Business concern**
  - Access policy must be explicit: client-facing roles exist in the product, but receiving print artifacts currently follow internal-only endpoint convention.
- **Operational implication**
  - Current architecture is symmetric with existing receiving print flows (labels/docs), producing consistent PDF artifacts and predictable internal governance.

## Business Rules and Constraints (Observed)

- **Soft lifecycle behavior is important**
  - `active` and `trashed` are central filtering axes in `build`.
  - Data appears intentionally retained rather than hard-deleted (especially users/entities/transactions in build semantics).

- **Client scoping matters**
  - Client admins/employees are filtered to their associated entities/shows.
  - Internal roles have broader scope; WP admin bypass behavior exists.

- **Billing/weight logic is critical**
  - Billable weight is computed from show-level minimum/increment configuration.
  - Missing or zero increment is treated as an edge case requiring guard logic.

- **Operational print artifacts are first-class outputs**
  - API endpoints return base64 PDFs for client rendering/download.
  - Print outputs are not a side feature; they are core to workflow execution.

## Technical-Operational Concerns

- **Permission correctness**
  - `build` has explicit permission mapping and role predicates; `source` is more permissive in route config.

- **Data consistency across evolving schema**
  - Significant differences in transaction schema/model assumptions suggest migration/data-contract risk.

- **Integration surface stability**
  - Route names/methods and payload fields differ enough that frontend/backend version mismatches are likely to break.

- **Auditability and recovery**
  - Soft-delete/status patterns imply business need to recover users/entities/records and preserve history.

## Delivery Status and Communication

- A client-ready status communication package is maintained in:
  - [`/Users/bkschatzki/Code/bigtb/super-logistics/docs/14-client-update-email-and-executive-summary.md`](/Users/bkschatzki/Code/bigtb/super-logistics/docs/14-client-update-email-and-executive-summary.md)
- It captures:
  - cross-version reconciliation context (`source` / `build` / `downloaded` / `repo`),
  - delivered fixes (manifest, tracking default export, POD print/view),
  - end-user testing instructions and explicit feedback requests for authorization/policy decisions.
