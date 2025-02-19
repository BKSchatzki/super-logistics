<?php

namespace BigTB\SL\API\Report\Controllers;

use BigTB\SL\API\PDF\reports\PalletManifestGenerator;
use BigTB\SL\API\PDF\reports\TrailerManifestGenerator;
use BigTB\SL\API\PDF\ShowReportGenerator;
use BigTB\SL\API\PDF\ShowReportGeneratorTwo;
use BigTB\SL\API\PDF\Transformers\PDFTransformer;
use BigTB\SL\API\Transaction\Models\Transaction;
use BigTB\SL\Setup\Core\Controller;
use League\Fractal\Resource\Item;
use WP_REST_Request;

class ReportsController extends Controller {

	public static function printTrailerManifest( WP_REST_Request $request ): array {
		$trailerNo    = $request->get_param( 'trailer_no' );
		$transactions = Transaction::with( [ 'zone' ] )
		                           ->where( 'trailer', $trailerNo )
		                           ->where( 'active', 1 )
		                           ->where( 'trashed', 0 )
		                           ->get();

		if ( $transactions->isEmpty() ) {
			self::sendErrorResponse( 'No transactions found for this trailer number' );
		}

		$reportGenerator = new TrailerManifestGenerator();
		$pdf             = $reportGenerator->generate( $transactions, $request->get_param( 'seal_no' ) );
		// Encode the PDF content to base64
		$pdfBase64 = base64_encode( $pdf );

		$res = new Item( [ 'pdf' => $pdfBase64 ], new PDFTransformer() );

		// Return the response
		return self::prepareArrayResponse( $res );
	}

	public static function printPalletManifest( WP_REST_Request $request ): array {
		$palletNo     = $request->get_param( 'pallet_no' );
		$transactions = Transaction::with(
			[ 'zone' ] )
		                           ->where( 'pallet', $palletNo )
		                           ->where( 'active', 1 )
		                           ->where( 'trashed', 0 )
		                           ->get();

		if ( $transactions->isEmpty() ) {
			self::sendErrorResponse( 'No transactions found for this pallet number' );
		}

		$reportGenerator = new PalletManifestGenerator();
		$pdf             = $reportGenerator->generate( $transactions );
		// Encode the PDF content to base64
		$pdfBase64 = base64_encode( $pdf );

		$res = new Item( [ 'pdf' => $pdfBase64 ], new PDFTransformer() );

		// Return the response
		return self::prepareArrayResponse( $res );
	}

}
