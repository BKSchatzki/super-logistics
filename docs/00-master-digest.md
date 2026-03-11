# Super Logistics Audit Digest

This digest was originally a comparative audit across `source/`, `build/`, `downloaded/`, and `repo/`. Those auxiliary directories have been retired; `repo/` (now the workspace root) is the sole source of truth at **v3.1.2**.

## Quick Conclusions

- **Source of truth**: `repo/` (v3.1.2). The `source/`, `build/`, and `downloaded/` directories were confirmed redundant and removed.
- **Client UAT (Feb 2026)**: D. Jones confirmed tracking export and manifest pagination working. POD feature was reworked per client feedback (see doc 15).
- **v3.1.1 delivers**: carrier POD upload system, "Seal No: undefined" fix, intra-row overflow hardening, printPOD access-control fix, gruntfile packaging fix.
- **v3.1.2 hotfix**: explicit transactions-table schema upgrade guard ensures `pod_path` is added when older environments missed the original table alteration (see doc 16).
- **All original audit findings** (docs 01-10) remain valid for historical context but reference directories that no longer exist in the workspace.

## Document Map

- [`01-repo-rundown-and-equivalence.md`](./01-repo-rundown-and-equivalence.md)
  High-level comparison, overlap metrics, and representative same-vs-different examples.

- [`02-difference-catalog.md`](./02-difference-catalog.md)
  Detailed catalog of architectural and behavioral differences between `source/` and `build/`.

- [`03-core-features-and-business-concerns.md`](./03-core-features-and-business-concerns.md)
  Thorough understanding of domain behavior, user types, and operational concerns.

- [`04-interchangeability-assessment.md`](./04-interchangeability-assessment.md)
  Practical answer to "can I build `source/` and get `build/`?" plus migration implications.

- [`05-infinite-loop-fix-analysis.md`](./05-infinite-loop-fix-analysis.md)
  Exact location and interpretation of the zero-increment loop fix, with contrast to `source/`.

- [`06-file-level-mapping-appendix.md`](./06-file-level-mapping-appendix.md)
  Fast file-family map of what moved, what changed, and what exists only on one side.

- [`07-vue-source-to-build-compatibility-hypothesis-test.md`](./07-vue-source-to-build-compatibility-hypothesis-test.md)
  Direct hypothesis test of whether a Vue build from `source/` can be dropped into `build/` and work perfectly.

- [`08-business-parity-and-feature-delta.md`](./08-business-parity-and-feature-delta.md)
  Determines whether `build/` is merely a refactor of `source/` or a true feature/behavior evolution while retaining core business purpose.

- [`09-new-context-integration-downloads-and-repo.md`](./09-new-context-integration-downloads-and-repo.md)
  Integrates findings from `downloaded/` and `repo/` into the overall project understanding.

- [`10-current-project-standing-and-source-of-truth.md`](./10-current-project-standing-and-source-of-truth.md)
  Declares current standing and recommended source-of-truth strategy for stewardship.

- [`11-report-pagination-incident-diagnosis.md`](./11-report-pagination-incident-diagnosis.md)
  Client-reported incident: pallet/trailer manifest data collapses to a single line on pages after the first. Root-cause trace through TCPDF `MultiCell` + `SetXY` desynchronization, corrective change set, rollback risk, and verification checklist.

- [`12-tracking-export-gap-analysis.md`](./12-tracking-export-gap-analysis.md)
  Root-cause trace and implementation status for tracking export: Version A (default tracking column selected) is applied, with follow-up options documented for strict always-on export contracts.

- [`13-pod-attachment-capability-gap-analysis.md`](./13-pod-attachment-capability-gap-analysis.md)
  Documents Path B POD implementation: dedicated internal-only endpoint, POD PDF generator, receiver-details "View/Print POD" action, and access-scope rationale.

- [`14-client-update-email-and-executive-summary.md`](./14-client-update-email-and-executive-summary.md)
  Client-facing status email draft plus internal executive summary, including implementation recap, testing instructions, and policy-feedback requests (especially authorization scope).

- [`15-v3.1.1-carrier-pod-and-fixes.md`](./15-v3.1.1-carrier-pod-and-fixes.md)
  v3.1.1 release notes: carrier POD upload system rework, seal_no fix, intra-row overflow hardening, printPOD authorization, and gruntfile packaging fix.

- [`16-v3.1.2-pod-schema-hotfix.md`](./16-v3.1.2-pod-schema-hotfix.md)
  v3.1.2 release notes: `pod_path` schema self-heal for older environments where the transactions table missed the original POD column addition.

## Scope Notes

- The billable-weight infinite-loop guard has been applied (see doc 05).
- Report pagination fixes have been applied to pallet and trailer manifest generators (see doc 11), with intra-row overflow hardening added in v3.1.1.
- Carrier POD system has been fully reworked in v3.1.1 per client feedback: separate `pod_path` field, file upload for PDF/JPEG, client view access with authorization scoping (see doc 15).
- v3.1.2 adds an explicit runtime schema upgrade guard so environments missing `sl_transactions.pod_path` can self-heal on load (see doc 16).
- Runtime PHP dependencies are present in `vendor/` for local execution and verification.
- Verification harness is in `tests/verify_manifest_pagination.php`.
- The `source/`, `build/`, and `downloaded/` directories have been removed from the workspace. Audit docs (01-10) remain for historical reference.
