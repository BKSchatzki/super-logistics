<?php

namespace BigTB\SL\API\Entity\Controllers;

use BigTB\SL\API\Entity\Models\Show;
use BigTB\SL\API\Entity\Models\ShowPlace;
use BigTB\SL\API\Entity\Transformers\ShowTransformer;
use BigTB\SL\Setup\Core\Controller;
use BigTB\SL\Setup\Core\ResponseManager;
use WP_REST_Request;

// zones are type 1
// booths are type 2
class ShowPlaceController extends Controller {
	use ResponseManager;

	public static function create( WP_REST_Request | array $request ): array {
		$params = $request instanceof WP_REST_Request ? $request->get_params() : $request;
		if ( ! self::checkIfProvided( $params, [ 'show_id', 'zones', 'booths' ] ) ) {
			return self::prepareErrorResponse( 'show_id, zones and booths are required' );
		}
		list( $zones, $booths, $show_id ) = $params;

		$rows = [];
		self::createBulkInsertRows( $zones, 1, $show_id, $rows );
		self::createBulkInsertRows( $booths, 2, $show_id, $rows );

		$success = ShowPlace::insert( $rows );
		if ( ! $success ) {
			return self::prepareErrorResponse( 'Failed to insert show places' );
		}

		return self::returnShow($show_id);
	}

	public static function update( WP_REST_Request $request ): array {
		$params = $request->get_params();
		if ( ! self::checkIfProvided( $params, [ 'id', 'name' ] ) ) {
			return self::prepareErrorResponse( 'Zone / Booth names and ids are required to update' );
		}

		$showPlace = ShowPlace::find( $params['id'] );
		$showPlace->name = $params['name'];
		$success = $showPlace->save();

		if ( ! $success ) {
			return self::prepareErrorResponse( 'Failed to update show place' );
		}

		return self::returnShow($showPlace->show_id);
	}

	public static function delete( WP_REST_Request $request ): array {
		$params = $request->get_params();
		if (isset($params['id'])) {
			$show_id = self::deleteOne( $params['id'] );
		} else if (isset($params['ids'])) {
			$show_id = self::deleteMany( $params['ids'] );
		} else {
			return self::prepareErrorResponse( 'id or ids are required to delete' );
		}

		return self::returnShow($show_id);
	}

	private static function deleteOne( $id ) {
		$showPlace = ShowPlace::find( $id );
		$showPlace->trashed = 1;
		$showPlace->save();
		return $showPlace->show_id;
	}

	private static function deleteMany( $ids ) {
		$show_id = null;
		foreach ( $ids as $id ) {
			$show_id = self::deleteOne( $id );
		}
		return $show_id;
	}

	private static function createBulkInsertRows( $places, $type, $show_id, $rows = [] ) {
		foreach ( $places as $p ) {
			$rows[] = [
				'type'    => $type,
				'show_id' => $show_id,
				'name'    => $p
			];
		}

		return $rows;
	}

	private static function returnShow( $show_id ) {
		$show = Show::find( $show_id )->with( 'zones', 'booths' );
		return self::singleResponse( $show, new ShowTransformer );
	}
}