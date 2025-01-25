<?php

namespace BigTB\SL\Setup\Core;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

trait ResponseManager {
	public static function prepareArrayResponse( $resource, $extra = [] ): array {
		return self::prepareResponse( $resource, $extra );
	}

	public static function prepareJSONResponse( $resource, $extra = [] ): string {
		return json_encode( self::prepareResponse( $resource, $extra ) );
	}

	public static function setCreatedBy( $resource ): object {
		$user                 = wp_get_current_user();
		$resource->created_by = $user->ID;
		$resource->updated_by = $user->ID;

		return $resource;
	}

	public static function setUpdatedBy( $resource ): object {
		$user                 = wp_get_current_user();
		$resource->updated_by = $user->ID;

		return $resource;
	}

	private static function prepareResponse( $resource, $extra = [] ): array {
		$manager = new Manager();
		$manager->setSerializer( new DataArraySerializer() );

		if ( isset( $_GET['with'] ) ) {
			$manager->parseIncludes( sanitize_text_field( wp_unslash( $_GET['with'] ) ) );
		}

		if ( $resource ) {
			$response = $manager->createData( $resource )->toArray();

		} else {
			$response = [];
		}

		return array_merge( $extra, $response );
	}

	public static function prepareUserNotFoundResponse(): array {
		return self::prepareErrorResponse( "User not found", 404 );
	}

	public static function prepareErrorResponse( $message = "Resource not found", $status = 400 ): array {
		return [
			'error'  => $message,
			'status' => $status
		];
	}

	public static function collectionResponse( $data, $transformer ): array {
		if ( $data->count() === 0 ) {
			return self::prepareArrayResponse( new Collection( [], $transformer ) );
		} else {
			return self::prepareArrayResponse( new Collection( $data, $transformer ) );
		}
	}

	public static function singleResponse( $data, $transformer ): array {
		return self::prepareArrayResponse( new Item( $data, $transformer ) );
	}

}