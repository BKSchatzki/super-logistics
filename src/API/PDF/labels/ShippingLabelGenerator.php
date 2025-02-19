<?php

namespace BigTB\SL\API\PDF\labels;

use SimplePie\Exception;

class ShippingLabelGenerator extends LabelGenerator {

	public int $bodyTextSize = 12;

	public function generate( array $labelInfo ): string|Exception {
		// We'll generate one page per piece
		$totalPages = (int) $labelInfo['total_pcs'];

		// If you do NOT want that initial blank page from the parent constructor,
		// you can remove it before looping:
		// $this->pdf->deletePage(1);

		for ( $currentPage = 1; $currentPage <= $totalPages; $currentPage ++ ) {
			// Add a new page for each piece
			// (If you removed the parent's AddPage, keep this one.)
			$this->pdf->AddPage();

			$this->writeLabel( $labelInfo, $currentPage, $totalPages );
		}

		// Output the PDF with all pages
		return $this->pdf->Output( 'label.pdf', 'S' );
	}

	private function writeLabel( array $labelInfo, int $currentPage, int $totalPages ): void {
		// Adjust margins smaller for A6 format (4x6). For example:
		$this->pdf->SetMargins( 5, 5, 5 );
		// Or skip if you prefer the defaults from the parent.

		// Use a slightly smaller base font for a 4x6 label
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );

		// ------------------------------------------------------
		// 1) Draw the QR code in the upper-left
		// ------------------------------------------------------
		$qrCodeData = [
			'shipper'        => $labelInfo['shipper'],
			'exhibitor'      => $labelInfo['exhibitor'],
			'show_id'        => (int) $labelInfo['show_id'],
			'zone_id'        => (int) $labelInfo['zone_id'],
			'booth'       => $labelInfo['booth'],
			'carrier'        => $labelInfo['carrier'],
			'tracking'       => $labelInfo['tracking'],
			'street_address' => $labelInfo['street_address'],
			'shipper_city'   => $labelInfo['shipper_city'],
			'shipper_state'  => $labelInfo['shipper_state'],
			'shipper_zip'    => $labelInfo['shipper_zip'],
			'freight_type'   => (int) $labelInfo['freight_type'],
			'total_pcs'      => (int) $labelInfo['total_pcs'],
		];
		$qrCodeData = json_encode( $qrCodeData );
		// Coordinates and size for a 4x6. Adjust as needed.
		$qrX    = 5;
		$qrY    = 5;
		$qrSize = 30; // 30 mm square

		// 'QRCODE,M' or 'QRCODE,Q' depending on error correction desired
		$this->pdf->write2DBarcode( $qrCodeData, 'QRCODE,L', $qrX, $qrY, $qrSize, $qrSize, null, 'N' );

		// ------------------------------------------------------
		// 2) Print “(currentPage) of (totalPages)” to the right of the QR code
		// ------------------------------------------------------
		// Move “cursor” to the right of QR
		$this->pdf->SetFont( 'helvetica', '', 50 );  // Slightly smaller than the large label example
		$this->pdf->SetXY( $qrX + $qrSize + 5, $qrY + 5 );
		$this->pdf->Cell( 0, 8, $currentPage . ' of ' . $totalPages, 0, 1, 'L' );

		// ------------------------------------------------------
		// 3) “From:” section
		// ------------------------------------------------------
		$this->pdf->SetXY( $qrX, $qrY + $qrSize + 5 );
		$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );
		$this->pdf->Cell( 0, 5, 'From:', 0, 1, 'L' );
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$fromLines = [
			$labelInfo['shipper'],
			$labelInfo['street_address'],
			$labelInfo['shipper_city'] . ', ' . $labelInfo['shipper_state'] . ' ' . $labelInfo['shipper_zip']
		];
		foreach ( $fromLines as $line ) {
			$this->pdf->Cell( 0, 4, $line, 0, 1, 'L' );
		}

		// ------------------------------------------------------
		// 4) “To:” section
		// ------------------------------------------------------
		$this->pdf->Ln( 3 );
		$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );
		$this->pdf->Cell( 0, 5, 'To:', 0, 1, 'L' );
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );

		// Hardcode Advance Warehouse Address
		$this->pdf->Cell( 0, 4, $labelInfo['exhibitor'], 0, 1, 'L' );
		$this->writeInfo( 'Care of', 'ThinkSTG Advance Warehouse', 17 );
		$toLines = [
			'751 West Warm Springs Rd. Ste. 140',
			'Henderson, NV, 89011',
			'702-251-8440'
		];
		foreach ( $toLines as $line ) {
			$this->pdf->Cell( 0, 4, $line, 0, 1, 'L' );
		}

		// ------------------------------------------------------
		// 5) Show / Zone / Booth
		// ------------------------------------------------------
		$this->pdf->Ln( 3 );
		$this->writeInfo( 'Show', $labelInfo['show']['name'], 14 );
		$this->writeInfo( 'Zone', $labelInfo['zone']['name'] );
		$this->writeInfo( 'Booth', $labelInfo['booth'], 15 );

		// ------------------------------------------------------
		// 6) Carrier / Freight / Tracking
		// ------------------------------------------------------
		$this->pdf->Ln( 2 );
		$this->writeInfo( 'Carrier', $labelInfo['carrier'], 16 );

		$freightTypes = [ 'LTL', 'FTL', 'Small Pack' ];
		$this->writeInfo( 'Freight Type', $freightTypes[ (int) $labelInfo['freight_type'] - 1 ], 28 );

		// Tracking number label
		$tracking = wordwrap( $labelInfo['tracking'] ?? '', 34 );
		$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );
		$this->pdf->MultiCell( 20, 4, "Tracking /Pro#", 0, 'L', 0, 0 );
		$this->pdf->Cell( 1, 4, ': ', 0, 0, 'C' );
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$this->pdf->MultiCell( 0, 0, $tracking, 0, 'L', 0, 0 );

		// Done with this page
	}

}
