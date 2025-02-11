<?php

namespace BigTB\SL\API\PDF\reports;

use Illuminate\Support\Collection;
use Carbon\Carbon;
use BigTB\SL\API\Transaction\Models\Transaction;

class TrailerManifestGenerator extends ReportGenerator
{
	protected int $bodyTextSize = 12;

	public function generate(Collection $transactions, $sealNo = '' ): string
	{
		$this->pdf->AddPage();

		// Document title at the top:
		$this->pdf->SetFont('helvetica', 'B', 31);
		$this->pdf->Cell(110, 12, 'Trailer Manifest', 1, 0, 'L');

		// Trailer Number
		$trailerNumber = $transactions->first()->trailer ?? 'N/A';
		$this->pdf->Cell(67, 12, $trailerNumber, 1, 0, 'L');

		// Seal Number
		$this->pdf->SetFont('helvetica', '', 31);
		$this->pdf->Cell(0, 12, 'Seal No: ' . $sealNo, 0, 1, 'L');

		$this->pdf->Ln(5);

		// Group by 'pallet'
		$groupedByPallet = [];
		foreach ($transactions as $tx) {
			$palletKey = 'Incomplete';
			if ($tx->pallet && $tx->pallet !== '') {
				$palletKey = $tx->pallet;
			}
			$groupedByPallet[$palletKey][] = $tx;
		}

		// For each pallet, print aggregate row, then sub-row table
		foreach ($groupedByPallet as $palletId => $group) {
			$this->writeAggregateRow($palletId, $group);
			// Now the sub-row table: "Receiver No. | Exhibitor | Shipper | Zone | Booth | Pcs | Weight | Received"
			$this->writeSubRowsHeader();
			foreach ($group as $tx) {
				$this->writeSubRow($tx);
			}
			$this->pdf->Ln(4);
		}

		return $this->pdf->Output('trailer_manifest.pdf', 'S');
	}

	/**
	 * Prints "Pallet [palletId]", "[sumPcs] pcs", "[sumWeight] lbs." in a single row.
	 */
	protected function writeAggregateRow(string $palletId, array $group): void
	{
		$sumPcs = 0;
		$sumWeight = 0;
		$palletRowHeight = 12;
		foreach ($group as $tx) {
			$sumPcs    += (int) $tx->total_pcs;
			$sumWeight += (float) $tx->total_weight;
		}

		$palletLabel = 'Pallet ' . $palletId;
		$pcsStr      = $sumPcs . ' pcs';
		$weightStr   = $sumWeight . ' lbs.';

		$this->pdf->SetFont('helvetica', 'B', $this->bodyTextSize);

		// Simple row with three cells
		$this->pdf->Cell(177, $palletRowHeight, $palletLabel, 1, 0, 'L');
		$this->pdf->Cell(20, $palletRowHeight, $pcsStr,      1, 0, 'L');
		$this->pdf->Cell(30,  $palletRowHeight, $weightStr,   1, 0, 'L');
		$this->pdf->Cell(0,  $palletRowHeight, '',   1, 1, 'L');
	}

	/**
	 * Sub-rows header for each pallet group.
	 */
	protected function writeSubRowsHeader(): void
	{
		$this->pdf->SetFont('helvetica', '', $this->bodyTextSize);

		$this->pdf->Cell(20, 6, 'Rec. No.', 1, 0, 'C');
		$this->pdf->Cell(45, 6, 'Exhibitor', 1, 0, 'C');
		$this->pdf->Cell(45, 6, 'Shipper',   1, 0, 'C');
		$this->pdf->Cell(47, 6, 'Zone',      1, 0, 'C');
		$this->pdf->Cell(20, 6, 'Booth',     1, 0, 'C');
		$this->pdf->Cell(20, 6, 'Pcs',       1, 0, 'C');
		$this->pdf->Cell(30, 6, 'Weight',    1, 0, 'C');
		$this->pdf->Cell(0,  6, 'Received',  1, 1, 'C');
	}

	/**
	 * Prints a single transaction row with wrapped text in exhibitor, shipper, zone, booth, etc.
	 */
	protected function writeSubRow(Transaction $tx): void
	{
		$this->pdf->SetFont('helvetica', '', $this->bodyTextSize);

		$zoneName  = $tx->zone->name ?? '';
		$boothName = $tx->booth->name ?? '';
		$weightStr = ($tx->total_weight ?? 0) . ' lbs.';
		$received  = Carbon::parse($tx->created_at)->format('M/d/y H:i');

		// Gather each cell's text
		$cellValues = [
			(string) $tx->id,         // Receiver
			(string) $tx->exhibitor,  // Exhibitor
			(string) $tx->shipper,    // Shipper
			$zoneName,                // Zone
			$boothName,               // Booth
			(string) $tx->total_pcs,  // Pcs
			$weightStr,               // Weight
			$received,                // Received
		];

		// Match widths from the header
		$cellWidths = [20, 45, 45, 47, 20, 20, 30, 0];
		$this->printRowMultiline($cellValues, $cellWidths);
	}

	/**
	 * Prints an array of values as a single row, using MultiCell with uniform height.
	 */
	protected function printRowMultiline(array $texts, array $widths): void
	{
		$startX = $this->pdf->GetX();
		$startY = $this->pdf->GetY();

		// Measure each cell's required height
		$heights = [];
		for ($i = 0; $i < count($texts); $i++) {
			// getStringHeight(width, text) to measure multiline space
			// third param: border = false, fourth param: multiline = true
			// last param: spacing factor = 1
			$heights[$i] = $this->pdf->getStringHeight($widths[$i], $texts[$i], false, true, '', 1);
		}

		$maxHeight = max($heights);

		// Print each cell as MultiCell
		for ($i = 0; $i < count($texts); $i++) {
			$cellW = $widths[$i];
			$this->pdf->MultiCell(
				$cellW,
				$maxHeight,
				$texts[$i],
				1,   // border
				'L', // alignment
				false, // fill
				0,   // line break (0 => to the right)
				'',  // x
				'',  // y
				true, // reset cursor
				0,    // stretch
				false, // is HTML
				true,  // autopadding
				$maxHeight // max height
			);
		}

		// Move the cursor to the start of this row + maxHeight
		$this->pdf->SetXY($startX, $startY + $maxHeight);
	}
}
