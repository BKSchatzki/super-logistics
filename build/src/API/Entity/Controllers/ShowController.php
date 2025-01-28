<?php

namespace BigTB\SL\API\Entity\Controllers;

use BigTB\SL\API\Entity\Models\Entity;
use BigTB\SL\API\Entity\Models\Show;
use BigTB\SL\API\Entity\Transformers\ShowTransformer;
use BigTB\SL\Setup\Routing\Permissions;
use Carbon\Carbon;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use WP_REST_Request;

// TODO: Build out show controller
// TODO: Test this controller
// TODO: Error Handling

class ShowController extends EntityController {

	static int $entityType = 2;
	static string $dateFormat = 'Y-m-d H:i:s';

	private static function setType( WP_REST_Request $request ): void {
		$request->set_param( 'type', self::$entityType );
	}

	public static function get( WP_REST_Request $request ): array {
		// Fetched with the ENTITY ID as the primary key

		self::setType( $request );
		$params = $request->get_params();

		// Fetch by show
		$query = self::addWhereClauses( Show::query()->with( [ 'entity', 'client', 'zones', 'booths' ] ), $params, [
			'client_id',
			'min_carat_weight',
			'carat_weight_inc',
			'date_start',
			'date_end',
		] );
		$shows = $query->get();

		// Fetch by entity
		if ( ! empty( array_intersect( $params, [
			'id',
			'name',
			'address',
			'city',
			'state',
			'zip',
			'phone',
			'email',
		] ) ) ) {
			$query    = self::addWhereClauses( Entity::query()->with( [ 'show.client' ] ), $params, [
				'id',
				'name',
				'type',
				'address',
				'city',
				'state',
				'zip',
				'phone',
				'email',
			] );
			$entities = $query->get();
			foreach ( $entities as $e ) {
				$show = Show::where( 'entity_id', $e->id )->with( [ 'entity', 'client', 'zones', 'booths' ] )->first();
				if ( $show ) {
					$shows->push( $show );
				}
			}
		}

		// Trim repeated shows
		$shows = $shows->unique( 'id' )->values();

		// Filter active users
		if ( Permissions::isAdmin() ) {
			if ( isset( $params['active'] ) ) {
				$shows = $shows->filter( function ( $show ) use ( $params ) {
					return $show->entity->active == $params['active'];
				} );
			}
		} else {
			$shows = $shows->filter( function ( $show ) {
				return $show->entity->active == 1;
			} );
		}

		// Filter trashed users
		if ( Permissions::isInternalAdmin() ) {
			if ( isset( $params['trashed'] ) ) {
				$shows = $shows->filter( function ( $show ) use ( $params ) {
					return $show->entity->trashed == $params['trashed'];
				} );
			}
		} else {
			$shows = $shows->filter( function ( $show ) {
				return $show->entity->trashed == 0;
			} );
		}

		return self::prepareArrayResponse( new Collection( $shows, new ShowTransformer ) );
	}

	public static function create( WP_REST_Request $request ): array {

		// Store images first
		$logoFile      = $request->get_file_params()['logoFile'] ?? null;
		$floorPlanFile = $request->get_file_params()['floorPlanFile'] ?? null;

		$logo_path       = $logoFile ? self::handleImageUpload( $logoFile ) : '';
		$floor_plan_path = $floorPlanFile ? self::handleImageUpload( $floorPlanFile ) : '';
		$params          = $request->get_params();

		error_log("SHOW PARAMS: " . print_r($params, true));

		if ( ! self::checkIfProvided( $params, [
			'name',
			'date_start',
			'date_end',
			'client_id',
		] ) ) {
			return self::prepareErrorResponse( 'Missing required fields' );
		}
		extract( $params );

		$date_start = trim( $date_start, '"' );
		$date_end   = trim( $date_end, '"' );

		// Use get_param() to retrieve each input field, handle empty/null values as needed
		$show_data   = [
			'date_start'       => Carbon::parse( $date_start )->format( self::$dateFormat ),
			'date_end'         => Carbon::parse( $date_end )->format( self::$dateFormat ),
			'min_carat_weight' => $min_carat_weight ?? null,
			'carat_weight_inc' => $carat_weight_inc ?? null,
			'client_id'        => $client_id,
			'floor_plan_path'  => $floor_plan_path,
		];
		$entity_data = [
			'name'      => sanitize_text_field( $name ),
			'type'      => self::$entityType,  // Static value
			'address'   => sanitize_text_field( $address ?? null ),
			'city'      => sanitize_text_field( $city ?? null ),
			'state'     => sanitize_text_field( $state ?? null ),
			'zip'       => sanitize_text_field( $zip ?? null ),
			'phone'     => sanitize_text_field( $phone ?? null ),
			'email'     => sanitize_email( $email ?? null ),
			'logo_path' => $logo_path,
		];

		// Create the related entity and show
		$entity          = Entity::create( $entity_data );
		$show            = $entity->show()->create( $show_data );
		$show->entity_id = $entity->id;
		$show->save();

		// Create zones and booths
		$zones = self::createBulkInsertRows( json_decode($params['zones']), 1 );
		$booths = self::createBulkInsertRows( json_decode($params['booths']), 2 );
		$show->places()->createMany( [...$zones, ...$booths] );

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

	public static function update( WP_REST_Request $request ): array {
		$show = Show::where( 'entity_id', $request->get_param( 'id' ) )->first();

		if ( ! $show ) {
			return self::prepareErrorResponse( 'Show not found' );
		}

		// Store images first
		$logoFile      = $request->get_file_params()['logoFile'] ?? null;
		$floorPlanFile = $request->get_file_params()['floorPlanFile'] ?? null;

		$logo_path       = $logoFile ? self::handleImageUpload( $logoFile ) : $show->entity->logo_path;
		$floor_plan_path = $floorPlanFile ? self::handleImageUpload( $floorPlanFile ) : $show->floor_plan_path;
		$params          = $request->get_params();

		if ( ! self::checkIfProvided( $params, [
			'name',
			'date_start',
			'date_end',
			'client_id',
		] ) ) {
			return self::prepareErrorResponse( 'Missing required fields' );
		}
		extract( $params );

		$date_start = trim( $date_start, '"' );
		$date_end   = trim( $date_end, '"' );

		// Use get_param() to retrieve each input field, handle empty/null values as needed
		$show_data = [
			'date_start'       => Carbon::parse( $date_start )->format( self::$dateFormat ),
			'date_end'         => Carbon::parse( $date_end )->format( self::$dateFormat ),
			'min_carat_weight' => $min_carat_weight ?? $show->min_carat_weight,
			'carat_weight_inc' => $carat_weight_inc ?? $show->carat_weight_inc,
			'client_id'        => $client_id,
			'floor_plan_path'  => $floor_plan_path,
		];

		$entity_data = [
			'name'      => sanitize_text_field( $name ),
			'address'   => sanitize_text_field( $address ?? $show->entity->address ),
			'city'      => sanitize_text_field( $city ?? $show->entity->city ),
			'state'     => sanitize_text_field( $state ?? $show->entity->state ),
			'zip'       => sanitize_text_field( $zip ?? $show->entity->zip ),
			'phone'     => sanitize_text_field( $phone ?? $show->entity->phone ),
			'email'     => sanitize_email( $email ?? $show->entity->email ),
			'logo_path' => $logo_path,
		];

		$show->update( $show_data );
		$show->entity->update( $entity_data );

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

	public static function markInactive( WP_REST_Request $request ): array {
		parent::markInactive( $request );
		$show = Entity::find( $request->get_param( 'id' ) )->show()->with( [ 'client', 'entity' ] )->first();

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

	public static function markActive( WP_REST_Request $request ): array {
		parent::markActive( $request );
		$show = Entity::find( $request->get_param( 'id' ) )->show()->with( [ 'client', 'entity' ] )->first();

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

	/**
	 * @param $zones1
	 * @param $show
	 *
	 * @return void
	 */
	public static function createBulkInsertRows( $names, $type ): array {
		$places = [];
		foreach ( $names as $name ) {
			$places[] = [ 'name' => $name, 'type' => $type ];
		}

		return $places;
	}

}
