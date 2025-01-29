<?php

namespace BigTB\SL\API\PDF\labels;

class AWLabelGenerator extends LabelGenerator {
	/**
	 * Size for our body text, used by writeInfo().
	 * Adjust if you want larger or smaller text.
	 */
	protected int $bodyTextSize = 14;

	public function generate( $labelInfo ): string {
		$this->configPDF( 5, 5, 5, 5);
		// Figure out how many pages we need:
		// If 'total_pcs' is not set, default to 1.
		$totalPieces = isset( $labelInfo['total_pcs'] ) ? (int) $labelInfo['total_pcs'] : 1;

		// If you do NOT want the extra blank page from LabelGenerator’s constructor:
		// $this->pdf->deletePage(1);

		// Add a page for each piece:
		for ( $currentPage = 1; $currentPage <= $totalPieces; $currentPage ++ ) {
			// Add a new page for each piece
			$this->pdf->AddPage();

			// Write the label contents
			$this->writeLabel( $labelInfo, $currentPage, $totalPieces );
		}

		// Return the PDF as a string
		return $this->pdf->Output( 'transaction_label.pdf', 'S' );
	}

	/**
	 * Writes the fields in an order similar to your screenshot,
	 * using the inherited writeInfo() method for each line.
	 */
	private function writeLabel( array $labelInfo, int $currentPage, int $totalPieces ): void {
		// Set a base font size (the parent's constructor sets one too, but let's ensure consistency)
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$this->writeInfo( 'Transaction', $labelInfo['id'], 30 );
		$this->writeInfo( 'Client', $labelInfo['client']->name, 16 );
		$this->writeInfo( 'Show', $labelInfo['show']->name, 16 );
		$this->writeInfo( 'Exhibitor', $labelInfo['exhibitor'], 25 );
		$this->writeInfo( 'Zone', $labelInfo['zone']->name, 15);
		$this->writeInfo( 'Booth', $labelInfo['booth']->name, 17 );
		$this->writeInfo( 'Pallet', $labelInfo['pallet'], 16 );
		$this->writeInfo( 'Trailer', $labelInfo['trailer'], 18 );
		$this->writeInfo( 'Carrier', $labelInfo['carrier'], 19 );

		// Tracking number is complicated because it's long
		$tracking = wordwrap( $labelInfo['tracking'] ?? '', 34 );
		$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );
		$this->pdf->MultiCell( 23, 4, "Tracking /Pro#", 0, 'L', 0, 0 );
		$this->pdf->Cell( 1, 4, ': ', 0, 0, 'C' );
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$this->pdf->MultiCell( 0, 0, $tracking, 0, 'L', 0, 1 );
		$this->pdf->Ln();
		$this->pdf->Ln();

		// Finally, "Total: (X) of (Y)"
		$pageCountText = $currentPage . ' of ' . $totalPieces;
		$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );
		$this->pdf->Cell( 14, 4, 'Total: ', 0, 0, 'L' );
		$this->pdf->SetXY( 20, 128 );
		$this->pdf->SetFont( 'helvetica', '', 40 );
		$this->pdf->Cell( 35, 4, $pageCountText, 0, 1, 'L' );
	}

	public function writeInfo(string $label, string|int $value, int $width = 12): void {
		parent::writeInfo($label, $value, $width);
		$this->pdf->Ln();
	}
}
