<?php

namespace BigTB\SL\API\Transaction\Controllers;

use BigTB\SL\API\Entity\Models\Entity;
use BigTB\SL\API\Entity\Models\ShowPlace;
use BigTB\SL\API\PDF\docs\ReceiverDocGenerator;
use BigTB\SL\API\PDF\labels\AWLabelGenerator;
use BigTB\SL\API\PDF\labels\ShippingLabelGenerator;
use BigTB\SL\API\PDF\Transformers\PDFTransformer;
use BigTB\SL\API\Transaction\Models\Transaction;
use BigTB\SL\API\Transaction\Transformers\TransactionTransformer;
use BigTB\SL\Setup\Core\Controller;
use League\Fractal\Resource\Item;
use SimplePie\Exception;
use WP_REST_Request;

class TransactionController extends Controller {

	public static function get( WP_REST_Request $request ): array {
		$query  = Transaction::query()->with( [ 'zone', 'show', 'booth' ] );
		$params = $request->get_params();
		$query  = self::addWhereClauses( $query, $params, [ 'id', 'show_id', 'zone_id', 'booth_id' ] );
		$query  = self::accessTrashedInactive( $query, $params );

		$transactions = $query->get();

		return self::collectionResponse( $transactions, new TransactionTransformer );
	}

	public static function create( WP_REST_Request $request ): array {

		$params = $request->get_params();

		if ( ! self::checkIfProvided( $params, [
			'shipper',
			'exhibitor',
			'show_id',
			'zone_id',
			'booth_id',
			'carrier',
			'tracking',
			'street_address',
			'shipper_city',
			'shipper_state',
			'shipper_zip',
			'freight_type',
			'crate_pcs',
			'carton_pcs',
			'skid_pcs',
			'fiber_case_pcs',
			'carpet_pcs',
			'misc_pcs',
			'total_pcs',
			'total_weight',
			'special_handling',
			'pallet',
			'trailer'
		] ) ) {
			return self::prepareErrorResponse( 'Missing required parameters to create transaction', 400 );
		}

		$user = wp_get_current_user();

		extract( $params );
		$transactionData = [
			'shipper'          => $shipper,
			'exhibitor'        => $exhibitor,
			'show_id'          => $show_id,
			'zone_id'          => $zone_id,
			'booth_id'         => $booth_id,
			'carrier'          => $carrier,
			'tracking'         => $tracking,
			'street_address'   => $street_address,
			'shipper_city'     => $shipper_city,
			'shipper_state'    => $shipper_state,
			'shipper_zip'      => $shipper_zip,
			'freight_type'     => $freight_type,
			'crate_pcs'        => $crate_pcs,
			'carton_pcs'       => $carton_pcs,
			'skid_pcs'         => $skid_pcs,
			'fiber_case_pcs'   => $fiber_case_pcs,
			'carpet_pcs'       => $carpet_pcs,
			'misc_pcs'         => $misc_pcs,
			'total_pcs'        => $total_pcs,
			'total_weight'     => $total_weight,
			'remarks'          => $remarks,
			'special_handling' => $special_handling === 'true' ? 1 : 0,
			'pallet'           => $pallet,
			'trailer'          => $trailer,
			'created_by'       => $user->ID,
			'updated_by'       => $user->ID,
		];

		$transaction = Transaction::create( $transactionData );

		return self::prepareArrayResponse( new Item( $transaction, new TransactionTransformer ) );
	}

	public static function update( WP_REST_Request $request ): array {
		$params = $request->get_params();

		$transaction = Transaction::find( $params['id'] );

		if ( ! $transaction ) {
			return self::prepareErrorResponse( 'Transaction not found', 404 );
		}

		$transactionData = [
			...$params,
			'updated_by' => wp_get_current_user()->ID,
		];

		$transaction->fill( $transactionData );
		$transaction->save();

		return self::prepareArrayResponse( new Item( $transaction, new TransactionTransformer ) );
	}

	public static function delete( WP_REST_Request $request ): array {
		$transaction          = Transaction::find( $request->get_param( 'id' ) );
		$transaction->trashed = 1;
		$transaction->save();

		return self::singleResponse( $transaction, new TransactionTransformer );
	}

	public static function markInactive( WP_REST_Request $request ): array {
		$transaction = Transaction::find( $request->get_param( 'id' ) );

		if ( ! $transaction ) {
			return self::prepareErrorResponse( 'Transaction not found', 404 );
		}

		$transaction->active = 0;
		$transaction->save();

		return self::singleResponse( $transaction, new TransactionTransformer );
	}

	public static function printShippingLabels( WP_REST_Request $request ): array {

		$params = $request->get_params();

		if ( ! self::checkIfProvided( $params, [
			'shipper',
			'exhibitor',
			'show_id',
			'zone_id',
			'booth_id',
			'carrier',
			'tracking',
			'street_address',
			'shipper_city',
			'shipper_state',
			'shipper_zip',
			'freight_type'
		] ) ) {
			return self::prepareErrorResponse( 'Missing required parameters to print shipping labels' );
		}

		// Fill in the related info
		$params = [ ...$params, ...self::getRemainingPrintData( $params ) ];

		// Create a new LabelGenerator and generate the PDF content
		$generator = new ShippingLabelGenerator();
		try {
			$pdf = $generator->generate( $params );
		} catch ( Exception $e ) {
			return self::prepareErrorResponse( $e, 500 );
		}
		$pdfBase64 = base64_encode( $pdf );
		$res       = new Item( [ 'pdf' => $pdfBase64 ], new PDFTransformer() );

		return self::prepareArrayResponse( $res );
	}

	public static function printAWLabels( WP_REST_Request $request ): array {

		$params = $request->get_params();

		if ( ! self::checkIfProvided( $params, [
			'id',
			'shipper',
			'exhibitor',
			'show_id',
			'zone_id',
			'booth_id',
			'carrier',
			'tracking',
			'street_address',
			'freight_type',
			'total_pcs'
		] ) ) {
			return self::prepareErrorResponse( 'Missing required parameters to print advance warehouse labels' );
		}

		// Fill in the related info
		$params           = [ ...$params, ...self::getRemainingPrintData( $params ) ];
		$params['client'] = Entity::find( $params['show']->show->client_id );

		// Create a new LabelGenerator and generate the PDF content
		$generator = new AWLabelGenerator();
		try {
			$pdf = $generator->generate( $params );
		} catch ( Exception $e ) {
			return self::prepareErrorResponse( $e, 500 );
		}
		$pdfBase64 = base64_encode( $pdf );
		$res       = new Item( [ 'pdf' => $pdfBase64 ], new PDFTransformer() );

		return self::prepareArrayResponse( $res );
	}

	public static function printReceiverDocs( WP_REST_Request $request ): array {

		$params = $request->get_params();

		if ( ! self::checkIfProvided( $params, [ 'id' ] ) ) {
			return self::prepareErrorResponse( 'Missing required parameters to print receiver documents' );
		}

		// Fill in the related info
		$transaction = Transaction::with(['show.entity', 'zone', 'booth'])->find( $params['id'] );
		$docData = $transaction->toArray();
		$docData['client'] = Entity::find( $docData['show']['client_id'] );

		// Create a new LabelGenerator and generate the PDF content
		$generator = new ReceiverDocGenerator();
		try {
			$pdf = $generator->generate( $docData );
		} catch ( Exception $e ) {
			return self::prepareErrorResponse( $e, 500 );
		}
		$pdfBase64 = base64_encode( $pdf );
		$res       = new Item( [ 'pdf' => $pdfBase64 ], new PDFTransformer() );

		return self::prepareArrayResponse( $res );
	}

	private static function getRemainingPrintData( $params ) {
		return [
			'show'  => Entity::with( [ 'show' ] )->find( $params['show_id'] ),
			'zone'  => ShowPlace::find( $params['zone_id'] ),
			'booth' => ShowPlace::find( $params['booth_id'] ),
		];
	}


}
