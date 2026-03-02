# New Context Integration: `downloaded/` and `repo/`

## Why This Update Exists

Initial documentation compared `source/` and `build/`.
This update incorporates two new, high-value contexts:

- `downloaded/`: plugin package downloaded from live site.
- `repo/`: transferred working repository from original developer.

## High-Level Outcome

- `repo/` is the strongest candidate for the active development baseline.
- `downloaded/` is the strongest candidate for current deployed package baseline.
- `build/` in this workspace is very close to `downloaded/`.
- `source/` remains an older divergent line and should not be treated as primary moving forward.

## Four-Directory Relationship Map

- `repo` -> (build pipeline) -> packaged release behavior similar to `downloaded`.
- `build` ~ `downloaded` for runtime plugin structure/assets/backend.
- `source` is materially divergent from `repo/build/downloaded` in backend and frontend contract.

## Quantified Comparison Highlights

Non-vendor/non-node comparison results:

- `build` vs `downloaded`
  - shared paths: 63
  - same hash: 61
  - meaningful code difference: no clear functional backend divergence found in reviewed diffs.
- `repo` vs `downloaded`
  - shared paths: 47
  - same hash: 46
  - **single code file difference:** `src/API/Transaction/Transformers/TransactionTransformer.php`
- `source` vs `repo`
  - shared paths: 38
  - same hash: 9
  - broad divergence across API/model/frontend paths.

## Most Important New Finding

`repo/src/API/Transaction/Transformers/TransactionTransformer.php` lacks the billable-weight zero-increment guard that exists in both `build` and `downloaded`.

Interpretation:

- The live/package line (`downloaded`) and local build line contain a safety fix.
- The transferred developer `repo` appears to miss that fix (or is behind that hotfix).

## Frontend/Backend Contract Status by Context

- `source` frontend -> `build` backend: contract mismatch (documented previously, still true).
- `repo` frontend -> `build/downloaded` backend: much closer and largely aligned in route families and request style (e.g., `users/current`, `users/logout`, `transactions/shipping`, `transactions/receiving/*`, report POSTs).

## Practical Standing

For stewardship:

- Treat `repo/` as primary development codebase.
- Treat `downloaded/` as release truth for “what is live now.”
- Keep `build/` as local package-style comparator.
- Treat `source/` as historical/legacy reference, not release candidate.
