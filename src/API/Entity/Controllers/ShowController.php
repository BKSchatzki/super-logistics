<?php

namespace BigTB\SL\API\Entity\Controllers;

use BigTB\SL\API\Entity\Models\Entity;
use BigTB\SL\API\Entity\Models\Show;
use BigTB\SL\API\Entity\Transformers\EntityTransformer;
use BigTB\SL\API\Entity\Transformers\ShowTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use WP_REST_Request;

// TODO: Build out show controller
// TODO: Test this controller
// TODO: Error Handling

class ShowController extends EntityController {

	static int $entityType = 2;

	private static function setType( \WP_REST_Request $request ): void {
		$request->set_param( 'type', self::$entityType );
	}

	public static function get( WP_REST_Request $request ): array {
		// Fetched with the ENTITY ID as the primary key

		self::setType( $request );
		$params = $request->get_params();

		// Fetch by show
		$query = self::addWhereClauses( Show::query()->with( ['entity', 'client'] ), $params, [
			'client_id',
			'min_carat_weight',
			'carat_weight_inc',
			'date_start',
			'date_end',
		] );
		$shows = $query->get();

		$query = self::addWhereClauses( Entity::query()->with( ['show.client'] ), $params, [
			'id',
			'name',
			'type',
			'address',
			'city',
			'state',
			'zip',
			'phone',
			'email',
			'logo_path',
		] );

		$entities = $query->get();
		foreach ( $entities as $e ) {
			$show = Show::where('entity_id', $e->id)->with('entity')->first();
			if ( $show ) {
				$shows->push( $show );
			}
		}

		error_log("SHOWS: " . print_r($shows, true));
		$shows = $shows->unique( 'id' )->values();
		error_log("UNIQUE SHOWS: " . print_r($shows, true));

		return self::prepareArrayResponse( new Collection( $shows, new ShowTransformer ) );
	}

	public static function create( WP_REST_Request $request ): array {

		// Store images first
		$logoFile      = $request->get_file_params()['logoFile'] ?? null;
		$floorPlanFile = $request->get_file_params()['floorPlanFile'] ?? null;

		$logo_path       = $logoFile ? self::handleImageUpload( $logoFile ) : '';
		$floor_plan_path = $floorPlanFile ? self::handleImageUpload( $floorPlanFile ) : '';
		$params          = $request->get_params();

		if ( ! self::checkIfProvided( $params, [
			'name',
			'date_start',
			'date_end',
			'client',
		] ) ) {
			return self::prepareErrorResponse( 'Missing required fields' );
		}
		extract( $params );

		$date_start = trim( $date_start, '"' );
		$date_end   = trim( $date_end, '"' );

		// Use get_param() to retrieve each input field, handle empty/null values as needed
		$show_data = [
			'date_start'       => Carbon::parse( $date_start )->format( 'Y-m-d H:i:s' ),
			'date_end'         => Carbon::parse( $date_end )->format( 'Y-m-d H:i:s' ),
			'min_carat_weight' => $min_carat_weight ?? null,
			'carat_weight_inc' => $carat_weight_inc ?? null,
			'client_id'        => $client,
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

		$entity = Entity::create( $entity_data );

		error_log( "ENTITY CREATED: " . print_r( $entity, true ) );

		$show            = $entity->show()->create( $show_data );
		$show->entity_id = $entity->id;
		$show->save();
		foreach ( json_decode( stripslashes( $_POST["places"] ), true ) as $data ) {
			$show->places()->create( $data );
		}

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

	public static function update( WP_REST_Request $request ): array {
		$show = Show::find( $request->get_param( 'id' ) );

		// Use get_param() to retrieve each input field, handle empty/null values as needed
		$show->date_start       = sanitize_text_field( $request->get_param( 'dateStart' ) );
		$show->date_end         = sanitize_text_field( $request->get_param( 'dateEnd' ) ) == 'null' ?
			null : sanitize_text_field( $request->get_param( 'dateEnd' ) );
		$show->date_expiry      = sanitize_text_field( $request->get_param( 'dateExpiry' ) ) == 'null' ?
			null : sanitize_text_field( $request->get_param( 'dateExpiry' ) );
		$show->min_carat_weight = sanitize_text_field( $request->get_param( 'minCaratWeight' ) );
		$show->carat_weight_inc = sanitize_text_field( $request->get_param( 'caratWeightInc' ) );
		$show->client_id        = sanitize_text_field( $request->get_param( 'clientID' ) );
		$show->floor_plan_path  = sanitize_text_field( $request->get_param( 'floorPlanPath' ) );

		$show->entity->name      = sanitize_text_field( $request->get_param( 'name' ) );
		$show->entity->address   = sanitize_text_field( $request->get_param( 'address' ) );
		$show->entity->city      = sanitize_text_field( $request->get_param( 'city' ) );
		$show->entity->state     = sanitize_text_field( $request->get_param( 'state' ) );
		$show->entity->zip       = sanitize_text_field( $request->get_param( 'zip' ) );
		$show->entity->phone     = sanitize_text_field( $request->get_param( 'phone' ) );
		$show->entity->email     = sanitize_email( $request->get_param( 'email' ) );
		$show->entity->logo_path = sanitize_text_field( $request->get_param( 'logoPath' ) );

		$show->save();
		$show->entity->save();

		$resource = new Item( $show, new ShowTransformer );

		return self::prepareArrayResponse( $resource );
	}

	public static function markInactive( WP_REST_Request $request ): array {
		$entity         = Entity::find( $request->get_param( 'id' ) );
		$entity->active = 0;
		$entity->save();

		$show = $entity->show;

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

	public static function delete( WP_REST_Request $request ): array {
		$entity = Entity::find( $request->get_param( 'id' ) );

		if ( ! $entity ) {
			return self::prepareErrorResponse( 'Entity not found' );
		}

		$show   = $entity->show;
		$show->delete();
		$entity->delete();

		return self::prepareArrayResponse( new Item( $show, new ShowTransformer ) );
	}

}
