<?php

namespace BigTB\SL\API\Transaction\Controllers;

use BigTB\SL\API\Entity\Models\Entity;
use BigTB\SL\API\Entity\Models\ShowPlace;
use BigTB\SL\API\PDF\Labels\ShippingLabelGenerator;
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
		$query  = self::addWhereClauses( $query, $params, [ 'id', 'show_id','zone_id', 'booth_id' ] );
		$query = self::accessTrashedInactive( $query, $params );

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
			'city',
			'state',
			'zip',
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
			'shipper_city'     => $city,
			'shipper_state'    => $state,
			'shipper_zip'      => $zip,
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

		if ( ! self::checkIfProvided( $params, [ 'id', 'name', 'show_id', 'zone' ] ) ) {
			return self::prepareErrorResponse( 'Missing required parameters to update transaction', 400 );
		}

		$transaction = Transaction::find( $params['id'] );

		if ( ! $transaction ) {
			return self::prepareErrorResponse( 'Transaction not found', 404 );
		}

		$transaction->fill( $params );
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
		$transaction         = Transaction::find( $request->get_param( 'id' ) );

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
			'city',
			'state',
			'zip',
			'freight_type',
			'total_pcs',
		] ) ) {
			return self::prepareErrorResponse( 'Missing required parameters to print shipping labels', 400 );
		}

		// Fill in the related info
		$params['show']  = Entity::with( [ 'show' ] )->find( $params['show_id'] );
		$params['zone']  = ShowPlace::find( $params['zone_id'] );
		$params['booth'] = ShowPlace::find( $params['booth_id'] );

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


}
