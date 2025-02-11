<?php

namespace BigTB\SL\API\Entity\Controllers;

use BigTB\SL\API\Entity\Models\Entity;
use BigTB\SL\API\Entity\Models\Show;
use BigTB\SL\API\Entity\Transformers\ShowTransformer;
use BigTB\SL\Setup\Routing\Permissions;
use Carbon\Carbon;
use League\Fractal\Resource\Item;
use WP_REST_Request;

class ShowController extends EntityController {

	static int $entityType = 2;
	static string $dateFormat = 'Y-m-d H:i:s';

	private static function setType( WP_REST_Request $request ): void {
		$request->set_param( 'type', self::$entityType );
	}

	public static function get( WP_REST_Request $request ): array {
		// Fetched with the ENTITY ID as the primary key
		// Area for optimization - try to fetch from a users assigned shows first

		// <editor-fold desc="Fetch Shows">---------------------

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
			// prepare query
			$query = self::addWhereClauses( Entity::query()->with( [ 'show.client' ] ), $params, [
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
			// get entities
			$entities = $query->get();
			// convert to show instances of shows
			foreach ( $entities as $e ) {
				$show = Show::where( 'entity_id', $e->id )->with( [ 'entity', 'client', 'zones', 'booths' ] )->first();
				if ( $show ) {
					$shows->push( $show );
				}
			}
		}

		// Trim repeated shows
		$shows = $shows->unique( 'entity_id' )->values();

		// </editor-fold>----------------------------------------

		// <editor-fold desc="Filtering"> -----------------------

		// Filter by assigned shows (if client)
		$shows = self::filterForClient( $shows );

		// Filter active shows
		$shows = self::filterByStatus( $shows, $params );

		// </editor-fold>----------------------------------------

		return self::collectionResponse( $shows, new ShowTransformer );
	}

	public static function create( WP_REST_Request $request ): array {

		//<editor-fold desc="Validate Parameters Provided">

		$params = $request->get_params();
		if ( ! self::checkIfProvided( $params, [
			'name',
			'date_start',
			'date_end',
			'client_id',
		] ) ) {
			self::sendErrorResponse( 'Missing required fields' );
		}
		extract( $params );

		//</editor-fold>

		// <editor-fold desc="Prepare Data for Insertion">---------------------

		// Format Dates
		$date_start = trim( $date_start, '"' );
		$date_end   = trim( $date_end, '"' );

		// Store images
		list( $logo_path, $floor_plan_path ) = self::storeImages( $request );

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

		// </editor-fold>-------------------------------------------------------

		// <editor-fold desc="Insert Data">--------------------------------------

		// Create the related entity and show
		$entity          = Entity::create( $entity_data );
		$show            = $entity->show()->create( $show_data );
		$show->entity_id = $entity->id;
		$show->save();

		// </editor-fold>-------------------------------------------------------

		// <editor-fold desc="Insert Zones and Booths">-------------------------

		$zones  = self::createBulkInsertRows( json_decode( $params['zones'] ), 1 );
		$booths = self::createBulkInsertRows( json_decode( $params['booths'] ), 2 );
		$show->places()->createMany( [ ...$zones, ...$booths ] );

		// </editor-fold>-------------------------------------------------------

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

	public static function update( WP_REST_Request $request ): array {

		// <editor-fold desc="Validate show being updated">------------------------

		$show = Show::where( 'entity_id', $request->get_param( 'id' ) )->first();
		if ( ! $show ) {
			self::sendErrorResponse( 'Show not found' );
		}

		// </editor-fold>----------------------------------------------------------

		// <editor-fold desc="Prepare Data for Update">----------------------------

		$params = $request->get_params(); // params are used later on in the function
		extract( $params );

		// Format Dates
		$date_start = trim( $date_start, '"' );
		$date_end   = trim( $date_end, '"' );

		// Store images
		list( $logo_path, $floor_plan_path ) = self::storeImages( $request );

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

		// </editor-fold>----------------------------------------------------------

		// <editor-fold desc="Update Data">----------------------------------------

		$show->update( $show_data );
		$show->entity->update( $entity_data );

		// </editor-fold>----------------------------------------------------------

		// <editor-fold desc="Update Zones and Booths">----------------------------

		self::updatePlaces( $show, $params, 1, 'zones' );
		self::updatePlaces( $show, $params, 2, 'booths' );

		// </editor-fold>----------------------------------------------------------

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

	public static function markInactive( WP_REST_Request $request ): array {
		parent::markInactive( $request );
		$show = Entity::find( $request->get_param( 'id' ) )->show()->with( [ 'client', 'entity', 'transactions' ] )->first();

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

	public static function markActive( WP_REST_Request $request ): array {
		parent::markActive( $request );
		$show = Entity::find( $request->get_param( 'id' ) )->show()->with( [ 'client', 'entity', 'transactions' ] )->first();

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

	public static function createBulkInsertRows( $names, $type ): array {
		$places = [];
		foreach ( $names as $name ) {
			$places[] = [ 'name' => $name, 'type' => $type ];
		}

		return $places;
	}

	private static function updatePlaces( $show, $params, $type, $typeName ): void {

		if ( ! isset( $params[ $typeName ] ) ) {
			return;
		}

		// Identify additions and deletions to make
		$existingPlaces = $show->places()->where( 'type', $type )->pluck( 'name' )->toArray();
		$placesToAdd    = array_diff( $params[ $typeName ], $existingPlaces );
		$placesToDelete = array_diff( $existingPlaces, $params[ $typeName ] );

		// Delete
		if ( ! empty( $placesToDelete ) ) {
			$show->places()->whereIn( 'name', $placesToDelete )->where( 'type', $type )->update( [ 'trashed' => true ] );
		}

		// Add
		if ( ! empty( $placesToAdd ) ) {
			$show->places()->createMany( self::createBulkInsertRows( $placesToAdd, $type ) );
		}
	}

	protected static function filterForClient( $shows ) {
		$currentUser = self::getCurrentUserModel();

		if ( $currentUser->roles->contains( 'client_admin' ) || $currentUser->roles->contains( 'client_employee' ) ) {
			$clientId = $currentUser->client->id ?? null;
			if ( $clientId ) {
				$shows = $shows->filter( function ( $show ) use ( $clientId ) {
					return $show->client_id == $clientId;
				} );
			}
			if ( $currentUser->roles->contains( 'client_employee' ) ) {
				$userShowIds = $currentUser->shows->pluck( 'entity_id' )->toArray();
				$shows       = $shows->filter( function ( $show ) use ( $userShowIds ) {
					return in_array( $show->id, $userShowIds );
				} );
			}
		}

		return $shows;

	}

	protected static function filterByStatus( $shows, $params = [] ) {

		// Filter active shows
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

		// Filter trashed shows
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

		return $shows;
	}

	private static function storeImages( $request ): array {
		$logoFile      = $request->get_file_params()['logoFile'] ?? null;
		$floorPlanFile = $request->get_file_params()['floorPlanFile'] ?? null;

		$logo_path       = $logoFile ? self::handleImageUpload( $logoFile ) : '';
		$floor_plan_path = $floorPlanFile ? self::handleImageUpload( $floorPlanFile ) : '';

		return [ $logo_path, $floor_plan_path ];
	}

}
