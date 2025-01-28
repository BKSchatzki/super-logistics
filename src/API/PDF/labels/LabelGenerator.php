<?php

namespace BigTB\SL\API\PDF\labels;

class LabelGenerator {

	public LabelPDF $pdf;

	public function __construct($orientation = 'P', $unit = 'mm', $format = array(102, 152)) {
		// in order, it goes like this:
		// orientation, unit, format, report
		$this->pdf = new LabelPDF($orientation, $unit, $format);
		$this->setMetaData();
		$this->configPDF();
	}

	public function setMetaData($creator = PDF_CREATOR, $author = 'ThinkSTG', $title = 'Shipping Label', $subject = 'Transaction'): void {
		$this->pdf->SetCreator( $creator );
		$this->pdf->SetAuthor( $author );
		$this->pdf->SetTitle( $title );
		$this->pdf->SetSubject( $subject );
	}

	public function configPDF($leftMargin = PDF_MARGIN_LEFT, $topMargin = PDF_MARGIN_TOP, $rightMargin = PDF_MARGIN_RIGHT, $headerMargin = PDF_MARGIN_HEADER, $footerMargin = PDF_MARGIN_FOOTER): void {
		// Set margins
		$this->pdf->SetMargins( $leftMargin, $topMargin, $rightMargin );
		$this->pdf->SetHeaderMargin( $headerMargin );
		$this->pdf->SetFooterMargin( $footerMargin );
		$this->pdf->SetAutoPageBreak( true, 0 );

		// Set image scale factor
		$this->pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );
		$this->pdf->SetFont( 'helvetica', '', 15 );
	}

	public function checkIfProvided($params, $required): bool {
		foreach ($required as $param) {
			if (!isset($params[$param])) {
				return false;
			}
		}
		return true;
	}

	public function writeInfo( string $label, string|int $value, int $width = 12 ): void {
		// Bold Label
		$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );
		$this->pdf->Cell( $width, 4, $label . ': ', 0, 0, 'L' );

		// Regular Text for info
		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$this->pdf->Cell( $width, 4, $value, 0, 1, 'L' );
	}

}