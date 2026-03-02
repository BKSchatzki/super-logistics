# Tracking Field CSV Export Analysis and Version A Status

## Request Intent

From the Jan 29, 2026 thread, client intent is that tracking (a pre-existing field) should be exportable in the receiver CSV workflow and appear as an obvious available outcome.

## Root Cause Trace (Confirmed)

- CSV export path is frontend DataTable export in [`/Users/bkschatzki/Code/bigtb/super-logistics/repo/view/components/receiver-management/ReceiverMgmtMain.vue`](/Users/bkschatzki/Code/bigtb/super-logistics/repo/view/components/receiver-management/ReceiverMgmtMain.vue) via `table.value.exportCSV()`.
- Export output is based on currently visible columns (`id` + `selectedColumns`), not all backend payload fields.
- `tracking` exists end-to-end (DB -> model/controller -> transformer -> table data), including transformer output in [`/Users/bkschatzki/Code/bigtb/super-logistics/repo/src/API/Transaction/Transformers/TransactionTransformer.php`](/Users/bkschatzki/Code/bigtb/super-logistics/repo/src/API/Transaction/Transformers/TransactionTransformer.php).
- The actual behavior mismatch was default selection: tracking existed, but it was not preselected for export.

## Version A Implementation (Applied)

- Updated default `selectedColumns` in [`/Users/bkschatzki/Code/bigtb/super-logistics/repo/view/components/receiver-management/ReceiverMgmtMain.vue`](/Users/bkschatzki/Code/bigtb/super-logistics/repo/view/components/receiver-management/ReceiverMgmtMain.vue) to include:
  - `shipper`
  - `exhibitor`
  - `nice_created_at`
  - `tracking`
- Kept current column selector behavior unchanged.

## Effective Behavior After Version A

- Export CSV without touching column chooser: tracking is now included by default.
- If user manually deselects tracking in the column chooser, CSV can still omit tracking (expected with Version A design).
- Re-selecting tracking re-includes it in CSV.

## Recommendations

- Keep Version A as the immediate production behavior (quick win, low risk).
- If business needs strict always-on tracking regardless of user column selection, queue a follow-up:
  - Option B: custom client-side export contract, or
  - Option C: backend export endpoint with fixed schema.

## Risk and Testing Notes

- Data/model risk: none (tracking pipeline already existed).
- Compatibility risk: low; no API or DB changes.
- Regression surface: limited to default receiver table column configuration.
- Manual smoke checks to run:
  - Default export includes tracking.
  - Deselect tracking, export omits tracking.
  - Re-add tracking, export includes tracking again.

## Confidence

High confidence. The issue was not missing tracking data or missing export capability in absolute terms; it was default export configuration and resulting user perception.
