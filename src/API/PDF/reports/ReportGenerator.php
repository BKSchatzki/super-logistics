<?php

namespace BigTB\SL\API\PDF\reports;

class ReportGenerator {

	public ReportPDF $pdf;

	/**
	 * Constructor sets up a portrait A4 document by default (210 x 297 mm).
	 *
	 * If you need different orientation/units/format, you can still override
	 * them when instantiating the class.
	 */
	public function __construct( string $orientation = 'L', string $unit = 'mm', array|string $format = [ 210, 297 ] ) {
		// orientation, unit, format
		$this->pdf = new ReportPDF( $orientation, $unit, $format );
		$this->setMetaData();
		$this->configPDF();
	}

	public function setMetaData( string $creator = PDF_CREATOR, string $author = 'ThinkSTG', string $title = 'Report', string $subject = 'Transaction'
	): void {
		$this->pdf->SetCreator( $creator );
		$this->pdf->SetAuthor( $author );
		$this->pdf->SetTitle( $title );
		$this->pdf->SetSubject( $subject );
	}

	public function configPDF( float $leftMargin = PDF_MARGIN_LEFT, float $topMargin = 10, float $rightMargin = PDF_MARGIN_RIGHT, float $bottomMargin = 11, float $headerMargin = PDF_MARGIN_HEADER, float $footerMargin = PDF_MARGIN_FOOTER
	): void {
		// Set margins
		$this->pdf->SetMargins( $leftMargin, $topMargin, $rightMargin );
		$this->pdf->SetHeaderMargin( $headerMargin );
		$this->pdf->SetFooterMargin( $footerMargin );
		$this->pdf->SetAutoPageBreak( true, 0 );
		$this->pdf->setCellPaddings(4, 0, 4, 0);

		// Bottom margin
		$this->pdf->SetAutoPageBreak(true, $bottomMargin);

		// Set image scale factor
		$this->pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );

		// Default font
		$this->pdf->SetFont( 'helvetica', '', 15 );
	}

	/**
	 * Helper to check for required parameters if needed.
	 */
	public function checkIfProvided( array $params, array $required ): bool {
		foreach ( $required as $param ) {
			if ( ! isset( $params[ $param ] ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * A sample method to write a label/value pair in bold + regular text.
	 * Uses $this->bodyTextSize if you have it in a child class;
	 * otherwise, ensure you set your font before calling this.
	 */
	public function writeInfo( string $label, string|int $value, int $width = 12 ): void {
		// Bold label
		$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize ?? 12 );
		$this->pdf->Cell( $width, 4, $label . ': ', 0, 0, 'L' );

		// Regular text
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize ?? 12 );
		$this->pdf->Cell( $width, 4, (string) $value, 0, 1, 'L' );
	}
}
