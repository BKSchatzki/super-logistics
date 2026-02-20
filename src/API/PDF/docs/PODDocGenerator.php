<?php

namespace BigTB\SL\API\PDF\docs;

use Carbon\Carbon;

class PODDocGenerator extends DocGenerator {

	protected int $bodyTextSize = 11;
	protected int $headingTextSize = 14;

	public function generate( array $data ): string {
		$this->pdf->AddPage();
		$this->buildHeader( $data );
		$this->buildDetails( $data );
		$this->buildPODSection( $data );

		return $this->pdf->Output( 'pod_document.pdf', 'S' );
	}

	protected function buildHeader( array $data ): void {
		$this->pdf->SetFont( 'helvetica', 'B', $this->headingTextSize );
		$this->pdf->Cell( 0, 10, 'ThinkSTG Proof of Delivery (POD)', 0, 1, 'L' );

		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$this->pdf->SetTextColor( 100, 100, 100 );
		$this->pdf->Cell( 0, 7, 'Receiver ' . (string) ( $data['id'] ?? '' ), 0, 1, 'L' );
		$this->pdf->SetTextColor( 0, 0, 0 );
	}

	protected function buildDetails( array $data ): void {
		$rows = [
			[ 'Client', $data['client']['name'] ?? '' ],
			[ 'Show', $data['show']['name'] ?? '' ],
			[ 'Zone', $data['zone']['name'] ?? '' ],
			[ 'Exhibitor', $data['exhibitor'] ?? '' ],
			[ 'Shipper', $data['shipper'] ?? '' ],
			[ 'Carrier', $data['carrier'] ?? '' ],
			[ 'Tracking', $data['tracking'] ?? '' ],
			[ 'Received', $this->formatDate( $data['created_at'] ?? null ) ],
		];

		$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
		$this->pdf->setCellPadding( 1.5 );
		foreach ( $rows as [ $label, $value ] ) {
			$this->pdf->SetFont( 'helvetica', 'B', $this->bodyTextSize );
			$this->pdf->Cell( 32, 7, $label . ':', 0, 0, 'L' );
			$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
			$this->pdf->Cell( 0, 7, (string) $value, 0, 1, 'L' );
		}
		$this->pdf->setCellPadding( 0 );
	}

	protected function buildPODSection( array $data ): void {
		$this->pdf->Ln( 2 );
		$this->pdf->SetFont( 'helvetica', 'B', 12 );
		$this->pdf->Cell( 0, 8, 'POD Attachment', 0, 1, 'L' );

		$imagePath         = $data['image_path'] ?? null;
		$resolvedImagePath = $this->resolveImagePath( $imagePath );
		if ( ! $resolvedImagePath ) {
			$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
			$this->pdf->MultiCell( 0, 8, 'No POD attachment is available for this receiver.', 1, 'L', 0, 1 );

			return;
		}

		$this->pdf->SetFont( 'helvetica', '', 9 );
		$this->pdf->SetTextColor( 100, 100, 100 );
		$this->pdf->MultiCell( 0, 6, 'Source: ' . (string) $imagePath, 0, 'L', 0, 1 );
		$this->pdf->SetTextColor( 0, 0, 0 );

		$startX = $this->pdf->GetX();
		$startY = $this->pdf->GetY();
		$maxW   = 180;
		$maxH   = 140;

		try {
			$this->pdf->Image( $resolvedImagePath, $startX, $startY, $maxW, $maxH, '', '', '', true, 300 );
			$this->pdf->SetY( $startY + $maxH + 2 );
		} catch ( \Throwable $e ) {
			$this->pdf->SetFont( 'helvetica', '', $this->bodyTextSize );
			$this->pdf->MultiCell(
				0,
				8,
				'POD attachment was found but could not be rendered in PDF. Error: ' . $e->getMessage(),
				1,
				'L',
				0,
				1
			);
		}
	}

	protected function resolveImagePath( ?string $imagePath ): ?string {
		if ( ! $imagePath ) {
			return null;
		}

		if ( file_exists( $imagePath ) ) {
			return $imagePath;
		}

		if ( filter_var( $imagePath, FILTER_VALIDATE_URL ) && function_exists( 'wp_upload_dir' ) ) {
			$uploads = wp_upload_dir();
			$baseUrl = rtrim( $uploads['baseurl'] ?? '', '/' );
			$baseDir = rtrim( $uploads['basedir'] ?? '', '/' );
			if ( $baseUrl && $baseDir && str_starts_with( $imagePath, $baseUrl ) ) {
				$relative = ltrim( substr( $imagePath, strlen( $baseUrl ) ), '/' );
				$mapped   = $baseDir . '/' . $relative;
				if ( file_exists( $mapped ) ) {
					return $mapped;
				}
			}

			// Fallback to URL if TCPDF can load remote paths in this environment.
			return $imagePath;
		}

		return null;
	}

	protected function formatDate( $date ): string {
		if ( ! $date ) {
			return '';
		}

		try {
			return Carbon::parse( $date )->setTimezone( 'America/Los_Angeles' )->format( 'F j, Y, g:i A' );
		} catch ( \Throwable $e ) {
			return (string) $date;
		}
	}
}
