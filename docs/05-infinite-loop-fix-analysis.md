# Infinite-Loop Fix Analysis (Billable Weight)

## Your Hypothesis

You suspected `build/` includes a fix for a loop that keeps adding zero to itself when capacities/weights are unset.

## Confirmation

Confirmed in:

- `build/src/API/Transaction/Transformers/TransactionTransformer.php`
- `downloaded/src/API/Transaction/Transformers/TransactionTransformer.php`
- Method: `calculateBillableWeight($item)`

The file explicitly contains an inline note:

- "OLD CODE (caused infinite loop if $increment = 0)"

Then it applies a guard:

- if increment is `<= 0`, return `max(minWeight, totalWeight)` instead of entering loop.

Only when increment is valid (`> 0`) does it run:

- `while ($billableWeight < $item->total_weight) { $billableWeight += $increment; }`

## Why This Prevents the Loop

Without the guard:

- If `billableWeight` starts below `total_weight` and `increment` is `0` (or null coerced), the loop condition never changes.

With the guard:

- Invalid increments short-circuit before loop entry.
- Loop progression is guaranteed only when increment is positive.

## Contrast with `source/`

- `source/src/API/Transaction/Transformers/TransactionTransformer.php` does not compute billable weight; it reads stored `billable_weight`.
- `source/src/API/PDF/ShowReportGenerator.php` and `ShowReportGeneratorTwo.php` do have billable-weight loops with break checks, but those are separate report-time calculations and not the same guard implementation.

## New Contrast with `repo/`

- `repo/src/API/Transaction/Transformers/TransactionTransformer.php` currently uses:
  - direct `while ($billableWeight < $item->total_weight) { $billableWeight += $increment; }`
  - without the defensive check for non-positive increments.
- This means the guard present in `build` and `downloaded` is not represented in `repo` as currently inspected.

## Practical Implication

This is a backend safety fix in the active package/runtime logic (`build`/`downloaded`), not a frontend/Vue build artifact.

Additional implication after new context integration:

- If releases are cut from `repo` without reconciling this method, the loop-risk fix can be lost.
