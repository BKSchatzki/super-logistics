<?php

namespace BigTB\SL\API\Entity\Controllers;

use BigTB\SL\API\Entity\Models\Entity;
use BigTB\SL\API\Entity\Transformers\EntityTransformer;
use BigTB\SL\Setup\Core\Controller;
use BigTB\SL\Setup\Routing\Permissions;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use WP_REST_Request;

//	TODO: test this controller

class EntityController extends Controller {
// This controller is for Clients, Carriers, and sl_entities is also responsible for Show information
// Clients are type 1,
// Shows are type 2, and
// Carriers are type 3

	public static int $entityType = 0;

	public static function get( WP_REST_Request $request ): array {
		// Fetching entities, then transform and return
		// type determines the type of entity, 1 for clients, 2 for shows, 3 for carriers
		// Use the ShowController to get shows
		$params = $request->get_params();

		$query = self::addWhereClauses( Entity::query(), $params, [
			'type',
			'active',
			'id',
			'name',
			'email',
			'address',
			'city',
			'state',
			'zip',
			'phone'
		] );

		// Modify query to filter active users
		$query = self::accessTrashedInactive( $query, $params );

		$entities = $query->get();
		$resource = new Collection( $entities, new EntityTransformer );

		return self::prepareArrayResponse( $resource );
	}

	public static function create( WP_REST_Request $request ): array {// This function is formData in order to handle the image file upload
		$logo_path = isset( $_FILES['logoFile'] ) ? self::handleImageUpload( $_FILES['logoFile'] ) : '';
		extract( $request->get_params() );
		$entity_data = [
			'name'      => sanitize_text_field( $name ),
			'type'      => sanitize_text_field( $type ),
			'address'   => sanitize_text_field( $address ?? '' ),
			'city'      => sanitize_text_field( $city ?? '' ),
			'state'     => sanitize_text_field( $state ?? '' ),
			'zip'       => sanitize_text_field( $zip ?? '' ),
			'phone'     => sanitize_text_field( $phone ?? '' ),
			'email'     => sanitize_email( $email ?? '' ),
			'logo_path' => $logo_path ?? ''
		];

		$entity = Entity::create( $entity_data );

		$resource = new Item( $entity, new EntityTransformer );

		return self::prepareArrayResponse( $resource );
	}

	public static function update( WP_REST_Request $request ): array {
		$logo_path           = isset( $_FILES['logoFile'] ) ? self::handleImageUpload( $_FILES['logoFile'] ) : '';
		$params              = $request->get_params();
		$params['logo_path'] = $logo_path;
		$entity              = Entity::find( $params['id'] );

		if ( ! $entity ) {
			return self::prepareErrorResponse( 'Entity not found', 404 );
		}

		self::updateIfProvided( $entity, $params, [
			'name',
			'type',
			'phone',
			'email',
			'address',
			'city',
			'state',
			'zip'
		] );

		$entity->save();

		$resource = new Item( $entity, new EntityTransformer );

		return self::prepareArrayResponse( $resource );
	}

	public static function markInactive( WP_REST_Request $request ): array {
		$entity = Entity::find( $request->get_param( 'id' ) );

		if ( ! $entity ) {
			return self::throwNotFoundError();
		}

		$entity->active = 0;
		$entity->save();

		return self::prepareArrayResponse( new Item( $entity, new EntityTransformer ) );
	}

	public static function markActive( WP_REST_Request $request ): array {
		$entity = Entity::find( $request->get_param( 'id' ) );

		if ( ! $entity ) {
			return self::prepareUserNotFoundResponse();
		}

		$entity->active = 1;
		$entity->save();

		return self::prepareArrayResponse( new Item( $entity, new EntityTransformer ) );
	}

	public static function delete( WP_REST_Request $request ): array {
		if ( self::$entityType !== 0 ) {
			$entity = Entity::where( 'id', $request->get_param( 'id' ) )->where( 'type', self::$entityType )->first();
		} else {
			$entity = Entity::where( 'id', $request->get_param( 'id' ) )->first();
		}

		if ( ! $entity ) {
			return self::prepareErrorResponse( 'Entity not found', 404 );
		}

		$entity->trashed = 1;
		$entity->save();

		return self::singleResponse( $entity, new EntityTransformer );
	}

	public static function restore( WP_REST_Request $request ): array {
		if ( self::$entityType !== 0 ) {
			$entity = Entity::where( 'id', $request->get_param( 'id' ) )->where( 'type', self::$entityType )->first();
		} else {
			$entity = Entity::where( 'id', $request->get_param( 'id' ) )->first();
		}

		if ( ! $entity ) {
			return self::prepareErrorResponse( 'Entity not found', 404 );
		}

		$entity->trashed = 0;
		$entity->save();

		return self::singleResponse( $entity, new EntityTransformer );
	}
}
