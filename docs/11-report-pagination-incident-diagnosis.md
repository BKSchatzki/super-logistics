# Report Pagination Incident Diagnosis

## Incident Summary

**Reported by:** D. Jones (client)
**Affected reports:** Pallet Manifest, Trailer Manifest
**Trigger condition:** Report data exceeds one page
**Symptom:** On pages after the first, all row data collapses onto effectively one line — the "typewriter stops advancing rows."

> "When there is more than 1 page of data for the reports (pallet manifest/trailer manifest) it is writing all of the data on a single line on the additional pages."

A previous fix was applied by the original developer during a Home Depot show earlier in the year but is no longer functioning.

---

## End-User Symptom Description

1. Page 1 of a pallet or trailer manifest renders correctly: bordered table rows with proper vertical spacing.
2. The moment a page break occurs (page 2+), row boundaries collapse. Multiple logical rows of data appear stacked at roughly the same vertical position, overlapping or running together as a single horizontal band of text.
3. The visual effect is as if the cursor stopped moving down after each row — every row prints at approximately the same Y coordinate on the new page.

---

## Library in Use

- **Library:** `tecnickcom/tcpdf` `^6.8` (declared in `repo/composer.json`).
- **Wrapper class:** `BigTB\SL\API\PDF\reports\ReportPDF` extends `TCPDF` with a blank `Header()` override and a page-number `Footer()`.
- **Base configuration** (`ReportGenerator::configPDF`): `SetAutoPageBreak(true, 11)` — TCPDF will auto-insert a page break when content reaches 11 mm from the bottom.

### Relevant TCPDF Mechanics

| Concept                                  | Behavior                                                                                                                                                                             |
| ---------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `MultiCell($w, $h, $txt, ..., $ln, ...)` | Draws a text block. `$ln=0` places the cursor to the **right** of the cell; `$ln=1` moves to the next line below.                                                                    |
| Auto page break                          | When the cursor Y + content height exceeds `pageHeight - breakMargin`, TCPDF internally calls `AddPage()`, resets cursor to the top margin of the new page, and continues rendering. |
| `SetXY($x, $y)`                          | Moves the cursor to an absolute position **on the current page**. It does not change the active page.                                                                                |
| `getStringHeight($w, $txt)`              | Returns the predicted rendered height for text in a cell of width `$w`, accounting for current font, padding, and line spacing.                                                      |

---

## Root Cause — Pallet Manifest (Primary)

**File:** `repo/src/API/PDF/reports/PalletManifestGenerator.php`
**Method:** `writeCellsWithMultiline()` (lines 119–143)

### Failing Sequence

1. `$startX` and `$startY` are captured at the **top** of the method (lines 121–122), before any drawing.
2. Cell heights are measured and the row's `$maxHeight` is computed.
3. **No remaining-space check is performed.** The method proceeds directly to draw all cells horizontally using `MultiCell($w, $maxHeight, ..., $ln=0, ...)`.
4. If the row straddles the page bottom, TCPDF auto-breaks **mid-row**: the cell that triggers the break finishes rendering at the top of the new page, and subsequent cells continue horizontally on page 2.
5. After all cells are drawn, the method executes `SetXY($startX, $startY + $maxHeight)` — but `$startY` still holds the Y value from **page 1** (e.g., 270 mm on a 297 mm page).
6. Because `SetXY` operates on the **current** page (now page 2), the cursor is placed at an extremely high Y value (e.g., 285 mm) on the new page — near its bottom.
7. The next row captures this corrupted Y as its new `$startY`, draws at the bottom, immediately triggers another auto-break to page 3, and the cycle repeats.

### Net Effect

Every row after the first page break renders its content at the top of a new page (from the auto-break continuation) while the cursor-advance logic (`SetXY`) keeps pointing to the bottom. Rows pile up at the top of each subsequent page at nearly the same Y coordinate, producing the "single line" / "typewriter stopped" appearance.

### Confidence: **Very High (95%)**

The code path from `generate()` → `writeTableRow()` → `writeCellsWithMultiline()` has zero remaining-space awareness and relies entirely on `SetXY` with pre-captured coordinates. This is a textbook auto-break desynchronization bug in TCPDF row rendering.

---

## Root Cause — Trailer Manifest (Secondary)

**File:** `repo/src/API/PDF/reports/TrailerManifestGenerator.php`
**Method:** `printRowMultiline()` (lines 130–173)

### Partial Fix Present

Unlike the pallet generator, this method **does** contain a remaining-space check (lines 144–151):

```php
if ($remainingSpace < $maxHeight) {
    $this->pdf->AddPage();
}
```

This prevents the stale-cursor desynchronization that plagues the pallet generator. However, two issues remain:

1. **No header redraw after page break.** After `AddPage()`, the sub-row column headers ("Rec. No. | Exhibitor | Shipper | …") are not reprinted. The continuation page lacks table context.
2. **No page-break check in `writeAggregateRow()`.** The pallet aggregate row ("Pallet 33D | 12 pcs | 45 lbs") uses simple `Cell` calls with no remaining-space awareness. If a pallet group boundary falls near the page bottom, the aggregate row can render at the bottom of one page with its sub-rows starting headerless on the next.

### Confidence: **High (85%)**

The remaining-space check is present for sub-rows, so the "all on one line" symptom is less severe here than in the pallet manifest. The missing header redraw and unguarded aggregate row are confirmed by code inspection.

### Why Trailer Looked Correct While Pallet Failed

- Trailer row printing already used `MultiCell` with `ln=1` on the final cell, so TCPDF naturally advanced to the next row.
- Pallet row printing used `ln=0` for every cell and then forced cursor movement with `SetXY(...)`, which is fragile when a break occurs mid-row.
- The pallet fix now mirrors trailer's end-of-row cursor behavior and adds atomic row rendering to avoid split rows.
- Pallet also had a tighter column-width budget than trailer; even a small overrun at page width can destabilize line breaks near bottom margins.

### Broader Diagnostic Findings

- The pallet table width must stay within printable width (`pageWidth - leftMargin - rightMargin`); values near or above that limit amplify page-break edge cases.
- Verification harness output (`verification-manifest-summary.json`) is useful for repeatability but has known blind spots:
  - page counters from `getNumPages()` are unreliable in this harness bootstrap path,
  - trailer "missing header pages" can be false positives when a page starts with continuation rows by design.
- Therefore, visual PDF inspection remains the source of truth for this incident.

---

## Corrective Change Set

### Fix 1 — `PalletManifestGenerator::writeCellsWithMultiline()`

- Remove manual row cursor rewinding (`SetXY($startX, $startY + $maxHeight)`), which is the key source of stale-Y desynchronization after page breaks.
- Render pallet rows with the same robust pattern used by trailer rows: `MultiCell(..., ln=0)` for interior cells and `ln=1` for the last cell to advance to the next row naturally.
- Use explicit multiline height measurement (`getStringHeight(..., false, true, '', 1)`) so row height accounts for wrapped values consistently.
- Add a preflight page-space check and move the row to a new page when the full row can fit there but not in remaining space on the current page.
- Use trailer's continuation-page paradigm wholesale: when a page break is triggered before a row, add a new page and redraw the table header before rendering the row.
- Restore pallet row cell paddings (`2,1,2,1`) so compact values like `Carrier 1` and `B-16` remain inline instead of wrapping awkwardly.
- After continuation-page header redraw, explicitly restore body font so the first data row on subsequent pages is not bolded.

### Fix 2 — `TrailerManifestGenerator::printRowMultiline()`

- Use a conservative remaining-space pre-check (`pageHeight - currentY - breakMargin <= maxHeight + buffer`) before each sub-row render.
- If insufficient space, call `AddPage()` and then `$this->writeSubRowsHeader()` to redraw column headers on the continuation page.
- Track which page last received sub-row headers; when the row writer enters a new page without a header stamp, redraw sub-row headers before writing data.

### Fix 3 — `TrailerManifestGenerator::writeAggregateRow()`

- Use a conservative remaining-space pre-check with aggregate-row height + sub-header height (+ small safety buffer) before drawing an aggregate row, to avoid orphaning a pallet header at the page bottom.

---

## Rollback Risk

**Low.** The changes are additive guards (space checks + header redraws) and a reordering of existing cursor-capture logic. No data model, API, or database changes are involved. The fix is isolated to two files in the PDF generation layer. If the fix introduces unexpected layout issues, reverting the two generator files restores the previous (broken) behavior.

---

## Verification Checklist

- [ ] Generate a pallet manifest with enough transactions to span 2+ pages. Confirm:
  - [ ] Rows on page 2+ are properly spaced vertically (no overlap/collapse).
  - [ ] Column headers ("Rec. | Exhibitor | Carrier | …") reappear at the top of each continuation page.
  - [ ] Page numbers in the footer are correct.
- [ ] Generate a trailer manifest with enough transactions across multiple pallets to span 2+ pages. Confirm:
  - [ ] Sub-rows on continuation pages are properly spaced.
  - [ ] Column headers ("Rec. No. | Exhibitor | Shipper | …") reappear after each page break.
  - [ ] Pallet aggregate rows do not orphan at the bottom of a page without their sub-rows.
- [ ] Generate single-page pallet and trailer manifests. Confirm no regression (layout unchanged).
- [ ] Test with a row containing long multiline text (e.g., lengthy remarks) that alone nearly fills a page. Confirm it renders correctly without triggering a secondary auto-break.

---

## Local Verification Run Notes (2026-02-20)

- A synthetic local harness generated multi-page pallet and trailer PDFs to stress page breaks (`docs/artifacts/verification-*.pdf`).
- The verifier previously had an autoload resolution bug: it loaded `BigTB\SL` classes from `downloaded/src` instead of `repo/src`. This made early verification runs non-authoritative for repo changes.
- Runtime dependencies are now available directly under `repo/vendor` (mirrored from `downloaded/vendor`) so verification and local execution can run without relying on `downloaded/`.
- Verifier script now lives at `repo/tests/verify_manifest_pagination.php`.
- Result: trailer manifests consistently retained row/cell structure across page breaks; pallet manifests required additional hardening beyond initial guards to eliminate cross-page row bleed.
- Caveat: local harness bootstrapping uses `downloaded/vendor` and emits TCPDF runtime warnings tied to this non-production bootstrap path, so low-level page counters/trace metrics are not fully trustworthy in this environment.
- Practical conclusion: treat the code-level fix as implemented and high-confidence for row-collapse prevention, but perform one in-app/staging verification pass using real report data before production release.

---

## v3.1.1 Addendum: Intra-row Overflow Hardening

Client UAT confirmed the inter-row pagination fix is working (no bunching on multi-page manifests). An additional intra-row hardening was applied in v3.1.1:

Both `PalletManifestGenerator::writeCellsWithMultiline()` and `TrailerManifestGenerator::printRowMultiline()` now clamp `$maxHeight` to the usable page height after any page-break-and-header-redraw. This prevents TCPDF auto-break from firing mid-row when a single row is taller than the full usable page height, causing the row to truncate gracefully rather than desynchronize the cursor.

Client UAT also confirmed the "Seal No: undefined" display bug in trailer manifests, which was fixed separately (see doc 15).
