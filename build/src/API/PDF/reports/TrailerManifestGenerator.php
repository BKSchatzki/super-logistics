<?php

namespace BigTB\SL\API\PDF\reports;

use Illuminate\Support\Collection; // If you use Laravel's collection, otherwise adapt

class TrailerManifestGenerator extends ReportGenerator
{
	protected int $bodyTextSize = 12;

	/**
	 * Creates a PDF grouped by 'pallet', showing sums of total_pcs & total_weight,
	 * and the latest received date among that group's transactions.
	 *
	 * @param Collection $transactions Array of Transaction models
	 */
	public function generate(Collection $transactions): string
	{
		// Add a page
		$this->pdf->AddPage();

		// <editor-fold desc="Header">--------------------------------------------

		$trailerNumber = $transactions[0]->trailer ?? 'N/A';

		// Header
		$this->pdf->SetFont('helvetica', 'B', 31);
		$this->pdf->Cell(110, 12, 'Trailer Manifest', 1, 0, 'L');
		$this->pdf->Cell(78,    12, $trailerNumber,    1, 1, 'L');

		// </editor-fold>--------------------------------------------------------

		$this->pdf->Ln(5);

		// <editor-fold desc="Table">---------------------------------------------

		// Group transactions by 'pallet'
		$groupedByPallet = [];
		foreach ($transactions as $tx) {
			$palletKey = $tx->pallet ?? 'N/A';
			$groupedByPallet[$palletKey][] = $tx;
		}

		// Table header
		$this->pdf->SetFont('helvetica', 'B', $this->bodyTextSize);
		$this->pdf->Cell(30, 8, 'Pallet ID',         1, 0, 'C');
		$this->pdf->Cell(30, 8, 'Total Pcs',         1, 0, 'C');
		$this->pdf->Cell(50, 8, 'Total Weight',      1, 0, 'C');
		$this->pdf->Cell(78,  8, 'Last Received Date',1, 1, 'C');

		// Table rows
		$this->pdf->SetFont('helvetica', '', $this->bodyTextSize);

		foreach ($groupedByPallet as $palletId => $group) {
			self::writeTableRow($palletId, $group);
		}

		// </editor-fold>--------------------------------------------------------

		// Output the PDF as a string
		return $this->pdf->Output('trailer_manifest.pdf', 'S');
	}

	public function writeTableRow($palletId, $group):void {
		// Sum total_pcs and total_weight
		// Identify the max created_at
		// If your code is purely array-based (no Laravel), do a manual loop:
		$sumPcs = 0;
		$sumWeight = 0;
		$latestDate = null;

		foreach ($group as $tx) {
			$sumPcs += (int) $tx->total_pcs;
			$sumWeight += (float) $tx->total_weight;

			// Check date
			$txCreated = strtotime($tx->created_at);
			if (!$latestDate || $txCreated > $latestDate) {
				$latestDate = $txCreated;
			}
		}

		// Format the date
		$lastReceivedDate = $latestDate ? date('M/d/y', $latestDate) : '';

		// If you want the weight with “ lbs.” at the end:
		$weightStr = $sumWeight . ' lbs.';

		// Print the row
		$this->pdf->Cell(30, 8, $palletId,        1, 0, 'C');
		$this->pdf->Cell(30, 8, $sumPcs,          1, 0, 'C');
		$this->pdf->Cell(50, 8, $weightStr,       1, 0, 'C');
		$this->pdf->Cell(78,  8, $lastReceivedDate,1, 1, 'C');
	}
}
