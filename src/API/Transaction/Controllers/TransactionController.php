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

		// <editor-fold desc="Data Validation">--------------------------

		$params = $request->get_params();
		if ( ! self::checkIfProvided( $params, [
			'shipper',
			'exhibitor',
			'show_id',
			'zone_id',
			'booth_id',
			'carrier',
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
		] ) ) {
			self::sendErrorResponse( 'Missing required parameters to create transaction' );
		}

		// </editor-fold>--------------------------------------------------

		// <editor-fold desc="Data Preparation">--------------------------

		if ( isset( $_FILES['image'] ) ) {
			$imagePath = self::handleImageUpload( $_FILES['image'] );
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
			'pallet'           => strtoupper($pallet),
			'trailer'          => $trailer,
			'image_path'       => $imagePath,
			'created_by'       => $user->ID,
			'updated_by'       => $user->ID,
		];

		// </editor-fold>--------------------------------------------------

		$transaction = Transaction::create( $transactionData );

		return self::singleResponse( $transaction, new TransactionTransformer );
	}

	public static function update( WP_REST_Request $request ): array {

		// <editor-fold desc="Data Validation">--------------------------

		$params      = $request->get_params();
		$transaction = Transaction::find( $params['id'] );
		if ( ! $transaction ) {
			self::sendErrorResponse( 'Transaction not found', 404 );
		}

		// </editor-fold>--------------------------------------------------

		// <editor-fold desc="Update Image if Different">--------------------------

		// if no existing image
		if ( isset( $_FILES['image'] ) && ! $transaction->image_path ) {

			$newImagePath         = self::handleImageUpload( $_FILES['image'] );
			$params['image_path'] = $newImagePath;

			// if existing image
		} elseif ( isset( $_FILES['image'] ) && $transaction->image_path ) {

			// Handle the new image upload
			$newImagePath = self::handleImageUpload( $_FILES['image'] );

			if ( is_wp_error( $newImagePath ) || ! $newImagePath ) {
				self::sendErrorResponse( $newImagePath );
			}

			// Determine equality by generating and comparing hashes of image content

			$newImageHash      = md5_file( $newImagePath );
			$existingImageHash = md5_file( $transaction->image_path );

			// Compare and delete the existing image if different
			if ( $existingImageHash && $existingImageHash !== $newImageHash ) {
				unlink( $transaction->image_path );
			}

			// Update the image path in the transaction data
			$params['image_path'] = $newImagePath;
		}

		// </editor-fold>--------------------------------------------------

		// <editor-fold desc="Update Transaction">--------------------------

		$transactionData = [
			...$params,
			'pallet'           => strtoupper($params['pallet']),
			'special_handling' => $params['special_handling'] === 'true' ? 1 : 0,
			'active'           => 1,
			'trashed'          => 0,
			'updated_by'       => wp_get_current_user()->ID,
		];

		$transaction->fill( $transactionData );
		$transaction->save();

		// </editor-fold>--------------------------------------------------

		return self::singleResponse( $transaction, new TransactionTransformer );
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
			self::sendErrorResponse( 'Transaction not found', 404 );
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
			'street_address',
			'shipper_city',
			'shipper_state',
			'shipper_zip',
		] ) ) {
			self::sendErrorResponse( 'Missing required parameters to print shipping labels' );
		}

		// Fill in the related info
		$params = [ ...$params, ...self::getRemainingPrintData( $params ) ];

		// Create a new LabelGenerator and generate the PDF content
		$generator = new ShippingLabelGenerator();
		try {
			$pdf = $generator->generate( $params );
		} catch ( Exception $e ) {
			self::sendErrorResponse( $e, 500 );
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
			self::sendErrorResponse( 'Missing required parameters to print advance warehouse labels' );
		}

		// Fill in the related info
		$params           = [ ...$params, ...self::getRemainingPrintData( $params ) ];
		$params['client'] = Entity::find( $params['show']->show->client_id );

		// Create a new LabelGenerator and generate the PDF content
		$generator = new AWLabelGenerator();
		try {
			$pdf = $generator->generate( $params );
		} catch ( Exception $e ) {
			self::sendErrorResponse( $e, 500 );
		}
		$pdfBase64 = base64_encode( $pdf );
		$res       = new Item( [ 'pdf' => $pdfBase64 ], new PDFTransformer() );

		return self::prepareArrayResponse( $res );
	}

	public static function printReceiverDocs( WP_REST_Request $request ): array {

		$params = $request->get_params();

		if ( ! self::checkIfProvided( $params, [ 'id' ] ) ) {
			self::sendErrorResponse( 'Missing required parameters to print receiver documents' );
		}

		// Fill in the related info
		error_log( "Params ID: " . $params['id'] );
		$transaction       = Transaction::with( [ 'show.entity', 'zone', 'booth' ] )->find( $params['id'] );
		if ( ! $transaction ) {
			self::sendErrorResponse( "Transaction not found, instead found $transaction: ", 404 );
		}
		$docData           = $transaction->toArray();
		$docData['client'] = Entity::find( $docData['show']['client_id'] );

		// Create a new LabelGenerator and generate the PDF content
		$generator = new ReceiverDocGenerator();
		try {
			$pdf = $generator->generate( $docData );
		} catch ( Exception $e ) {
			self::sendErrorResponse( $e, 500 );
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
