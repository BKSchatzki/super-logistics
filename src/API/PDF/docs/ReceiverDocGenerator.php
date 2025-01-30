<?php

namespace BigTB\SL\API\PDF\docs;

use Carbon\Carbon;

class ReceiverDocGenerator extends DocGenerator {

	protected int $bodyTextSize = 12;
	protected int $fieldLabelSize = 8;
	protected int $rowH = 14;
	protected int $skipLineSize = 6;
	protected int $widthForHalf = 94;

	/**
	 * Generates a 2‐page PDF:
	 *   1) "Show Management Copy"
	 *   2) "Trailer Copy"
	 */
	public function generate( array $data ): string {
		$copies = [
			'Show Management Copy',
			'Trailer Copy',
			'Warehouse Copy'
		];

		foreach ( $copies as $copyLabel ) {
			$this->pdf->AddPage();
			$this->buildPage( $data, $copyLabel );
		}

		// Return the final PDF as a string
		return $this->pdf->Output( 'receiver_document.pdf', 'S' );
	}

	/**
	 * Builds one page (header, main tables, footer),
	 * using $copyLabel as the top‐right corner text.
	 */
	protected function buildPage( array $data, string $copyLabel ): void {
		$this->buildHeaderRow( $data, $copyLabel );
		$this->buildInfoTable( $data );
		$this->pdf->Ln( $this->skipLineSize );
		$this->buildDatesRow( $data );
		$this->pdf->Ln( $this->skipLineSize );
		$this->buildItemsTable( $data );
		$this->pdf->Ln( $this->skipLineSize );
		$this->buildFooter();
		$this->buildTrackingBox( $data );
	}

	/**
	 * Top row: 3 columns
	 *   1) "ThinkSTG Receiver Document"
	 *   2) "Transaction XXX"
	 *   3) "$copyLabel"
	 */
	protected function buildHeaderRow( array $data, string $copyLabel ): void {
		$transaction = $data['id'];

		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );

		// ~190 mm total width inside default margins -> 3 columns of ~63 mm each
		$colW = 63;
		$rowH = $this->rowH;

		$this->pdf->Cell( $colW, $rowH, 'ThinkSTG Receiver Document', 0, 0, 'C' );
		$this->pdf->Cell( $colW, $rowH, 'Transaction ' . $transaction, 0, 0, 'C' );
		$this->pdf->Cell( 0, $rowH, $copyLabel, 0, 1, 'C' );
	}

	/**
	 * Information table with 4 rows × 2 columns
	 */
	protected function buildInfoTable( array $data ): void {
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );

		$colW = $this->widthForHalf;
		$rowH = $this->rowH;

		error_log( "Data: " . print_r( $data, true ) );

		// Row 1
		$this->writeTableCell( $colW, $rowH, 'Exhibitor', $data['exhibitor'] );
		$this->writeTableCell( 0, $rowH, 'Origin / Shipper', $data['shipper'], true );

		// Row 2
		$this->writeTableCell( $colW, $rowH, 'Show', $data['show']['entity']['name'] );
		$this->writeTableCell( 0, $rowH, 'Carrier', $data['carrier'], true );

		// Row 3
		$this->writeTableCell( $colW, $rowH, 'Zone', $data['zone']['name'] );
		$this->writeTableCell( 0, $rowH, 'City / State / Zip', "{$data['shipper_city']}, {$data['shipper_state']}, {$data['shipper_zip']}", true );

		// Row 4
		$this->writeTableCell( $colW, $rowH, 'Booth Number', $data['booth']['name'], true );

	}

	/**
	 * Dates row: "Received Advance Warehouse" | "Last Updated by Advance Warehouse"
	 */
	protected function buildDatesRow( array $data ): void {
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );

		$colW = $this->widthForHalf;
		$rowH = $this->rowH;

		$this->writeTableCell( $colW, $rowH, 'Received Advance Warehouse', $this->formatDate($data['created_at']) );
		$this->writeTableCell( 0, $rowH, 'Last Updated by Advance Warehouse', $this->formatDate($data['updated_at']), true );

		$this->writeTableCell( $colW, $rowH, 'Unload Date', '' );
		$this->writeTableCell( 0, $rowH, 'Unload Time', '', true );
	}

	/**
	 * Item table with "Item" | "# Pieces" | "Tracking / Pro Number"
	 */
	protected function buildItemsTable( array $data ): void {
		// Static example items
		$items = [
			[ 'name' => 'Carpets', 'pieces' => $data['carpet_pcs'] ],
			[ 'name' => 'Cartons', 'pieces' => $data['carton_pcs'] ],
			[ 'name' => 'Crates', 'pieces' => $data['crate_pcs'] ],
			[ 'name' => 'Fiber Cases', 'pieces' => $data['fiber_case_pcs'] ],
			[ 'name' => 'Skids', 'pieces' => $data['skid_pcs'] ],
			[ 'name' => 'Misc.', 'pieces' => $data['misc_pcs'] ],
		];

		$colW1 = 33;
		$colW2 = 25;
		$colW3 = 0;   // auto width
		$rowH  = $this->rowH;

		// Table header
		$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );
		$this->pdf->Cell( $colW1, $rowH, 'Item', 1, 0, 'C' );
		$this->pdf->Cell( $colW2, $rowH, '# Pieces', 1, 0, 'C' );
		$this->pdf->Cell( $colW3, $rowH, 'Tracking / Pro Number', 1, 1, 'C' );

		// Table rows
		$this->pdf->setCellPaddings(4, 0, 4, 0);
		foreach ( $items as $item ) {
			$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );
			$this->pdf->Cell( $colW1, $rowH, $item['name'], 1, 0, 'L' );
			$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
			$this->pdf->Cell( $colW2, $rowH, $item['pieces'], 1, 1, 'C' );
		}

		// Totals row
		$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );
		$this->pdf->Cell( $colW1, $rowH, 'TOTALS', 1, 0, 'L' );
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$this->pdf->Cell( $colW2, $rowH, $data['total_pcs'], 1, 1, 'C' );
		$this->pdf->setCellPadding(0);
	}

	protected function buildFooter(): void {
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$colW = 63;
		$rowH = $this->rowH;

		// Big text row
		$this->pdf->MultiCell( 0, $rowH, "This is to certify the above listed items were unloaded and exceptions, if any, are correct:", 0, 'L', 0, 1 );

		// "Received By" / "Trucking Company" / "Driver"
		$this->writeHandwriteCell( $colW, $rowH, 'Received By:', '', false, 1, 'L' );
		$this->writeHandwriteCell( $colW, $rowH, 'Trucking Company:', '', false, 1, 'L' );
		$this->writeHandwriteCell( 0, $rowH, 'Driver:', '', true, 1, 'L' );
	}

	protected function buildTrackingBox(array $data): void {
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$colW = 0;
		$rowH = $this->rowH * 7;

		// Big text row
		$this->pdf->SetXY( 73, 141.75 );
		$this->pdf->setCellPadding(4);
		$this->pdf->MultiCell( $colW, $rowH, $data['tracking'], 1, 'L', 0 );
		$this->pdf->setCellPadding(0);
	}

	protected function writeTableCell( float $width, float $height, string $label, string $value, bool $ln = false, int $border = 1, string $align = 'L' ): void {
		// Build an HTML snippet with two lines:
		// Line 1: smaller font for $label
		// Line 2: bigger font for $value
		// We rely on $this->fieldLabelSize and $this->bodyTextSize being set in the class
		$html = sprintf(
			'<div>
            <span style="font-size:%dpt;">%s</span><br>
            <span style="font-size:%dpt; line-height: 2">%s</span>
        </div>',
			$this->fieldLabelSize,
			htmlspecialchars( $label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' ),
			$this->bodyTextSize,
			htmlspecialchars( $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' )
		);

		// TCPDF's writeHTMLCell parameters:
		//   width, height, x, y, html, border, ln, fill, reset, align
		$lineBreak = $ln ? 1 : 0; // if true, move cursor to next line after this cell
		$this->pdf->writeHTMLCell( $width, $height, '', '', $html, $border, $lineBreak, false, true, $align );
	}

	protected function writeHandwriteCell( float $width, float $height, string $label, string $value, bool $ln = false, int $border = 1, string $align = 'L' ): void {
		// We build an HTML snippet with two lines of text:
		//  First line: the "value" in larger font (bodyTextSize)
		//  Second line: the "label" in smaller font (fieldLabelSize)

		// Convert special characters to HTML entities to avoid breaking HTML
		$safeValue = htmlspecialchars( $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' );
		$safeLabel = htmlspecialchars( $label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' );

		$html = sprintf(
			'<div style="padding: 0;">
            <span style="font-size:%dpt; line-height:2">%s</span><br>
            <span style="font-size:%dpt; line-height:1">%s</span>
        </div>',
			$this->bodyTextSize,
			$safeValue,
			$this->fieldLabelSize,
			$safeLabel
		);

		// If $ln is true, move to a new line after printing this cell
		$moveCursorAfterCell = $ln ? 1 : 0;

		// writeHTMLCell(...): width, height, x, y, html, border, ln, fill, reset, align
		$this->pdf->writeHTMLCell( $width, $height, '', '', $html, $border, $moveCursorAfterCell, false, true, $align
		);
	}

	protected function formatDate( string $date ): string {
		return Carbon::parse( $date )->setTimezone('America/Los_Angeles')->format( 'F j, Y, g:i A' );
	}

}
