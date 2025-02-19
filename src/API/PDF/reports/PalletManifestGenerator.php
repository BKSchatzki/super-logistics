<?php

namespace BigTB\SL\API\PDF\reports;

use BigTB\SL\API\Transaction\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PalletManifestGenerator extends ReportGenerator {
	protected int $bodyTextSize = 10;

	/**
	 * Generates a single-page PDF listing every transaction
	 * in the given collection (all sharing the same pallet).
	 */
	public function generate( Collection $transactions ): string {
		// Add one page
		$this->pdf->AddPage();

		// Assume all transactions share the same pallet
		$palletNumber = $transactions->first()->pallet ?? 'N/A';

		// Print the title row (e.g. "Pallet Manifest" | "33D")
		$this->buildHeader( $palletNumber );

		// Table header (9 columns)
		$this->buildTableHeader();

		// Print each transaction row
		foreach ( $transactions as $tx ) {
			$this->writeTableRow( $tx );
		}

		// Output the PDF
		return $this->pdf->Output( 'pallet_manifest.pdf', 'S' );
	}

	/**
	 * Prints the title row: e.g. [Pallet Manifest] [33D]
	 */
	protected function buildHeader( string $palletNumber ): void {
		$this->pdf->SetFont( 'helvetica', 'B', 31 );
		$this->pdf->Cell( 100, 12, 'Pallet Manifest', 1, 0, 'L' );
		$this->pdf->Cell( 62, 12, $palletNumber, 1, 1, 'L' );

		// A little spacing before the table
		$this->pdf->Ln( 5 );
	}

	/**
	 * Prints the table header row:
	 *  Rec. | Exhibitor | Carrier | Tracking | Zone | Booth | Pcs. | Remarks | Received
	 */
	protected function buildTableHeader(): void {
		$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );

		// Column widths (adjust to fit your layout)
		$headerCols = [
			[ 'w' => 15, 'label' => 'Rec.' ],
			[ 'w' => 30, 'label' => 'Exhibitor' ],
			[ 'w' => 20, 'label' => 'Carrier' ],
			[ 'w' => 47, 'label' => 'Tracking' ],
			[ 'w' => 40, 'label' => 'Zone' ],
			[ 'w' => 15, 'label' => 'Booth' ],
			[ 'w' => 12, 'label' => 'Pcs.' ],
			[ 'w' => 47, 'label' => 'Remarks' ],
			[ 'w' => 18, 'label' => 'Trailer' ],
			[ 'w' => 24, 'label' => 'Received' ], // 0 => fill remaining width
		];

		foreach ( $headerCols as $col ) {
			$this->pdf->Cell( $col['w'], 8, $col['label'], 1, 0, 'C' );
		}
		$this->pdf->Ln( 8 );
	}

	/**
	 * For each Transaction, we create a table row of 9 columns.
	 * Some columns are multiline (tracking, remarks, received).
	 */
	protected function writeTableRow( Transaction $tx ): void {
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$this->pdf->setCellPaddings( 2, 1, 2, 1 );
		// We'll gather the cell text in an array.
		// "Received" is "created_by + new line + date/time"
		$receivedText = Carbon::parse( $tx->created_at )->format( 'M/d/y H:i' );

		// If zone or booth exist, we show their "name" property, else blank
		$zoneName  = $tx->zone->name ?? '';
		$trailer   = 'Incomplete';
		if ($tx->trailer && $tx->trailer !== '') {
			$trailer = $tx->trailer;
		}

		// The columns in the same order as the header
		$cells = [
			(string) $tx->id,        // Rec.
			(string) $tx->exhibitor, // Exhibitor
			(string) $tx->carrier,   // Carrier
			(string) $tx->tracking,     // Tracking (multiline possible)
			$zoneName,               // Zone
			(string) $tx->booth,              // Booth
			(string) $tx->total_pcs, // Pcs.
			(string) $tx->remarks,      // Remarks (multiline possible)
			$trailer,				// Trailer
			$receivedText,           // Received (multiline)
		];

		// Matching column widths
		$colWidths = [ 15, 30, 20, 47, 40, 15, 12, 47, 18, 24 ];

		$this->writeCellsWithMultiline( $cells, $colWidths );
	}

	/**
	 * A helper that prints a single "row" of cells,
	 * each potentially multiline, so they align nicely.
	 */
	protected function writeCellsWithMultiline( array $cells, array $widths ): void {
		// Remember the current X, Y
		$startX = $this->pdf->GetX();
		$startY = $this->pdf->GetY();

		// Calculate heights for each cell
		$heights = [];
		for ( $i = 0; $i < count( $cells ); $i ++ ) {
			$w = $widths[ $i ];
			// getStringHeight(width, text) helps measure multiline
			// We'll allow some margin for padding
			$heights[ $i ] = $this->pdf->getStringHeight( $w, $cells[ $i ] );
		}
		// The row's height is the max of all cell heights
		$maxHeight = max( $heights );

		// Print each cell
		for ( $i = 0; $i < count( $cells ); $i ++ ) {
			$w = $widths[ $i ];
			$this->pdf->MultiCell( $w, $maxHeight, $cells[ $i ], 1, 'L', false, 0, '', '', true );
		}

		// Move down to next line
		$this->pdf->SetXY( $startX, $startY + $maxHeight );
	}
}
