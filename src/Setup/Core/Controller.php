<?php

namespace BigTB\SL\Setup\Core;

use BigTB\SL\Setup\Routing\Permissions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class Controller {

	use ResponseManager;

	public static function accessTrashedInactive($query, $params): QueryBuilder {
		// Modify query to filter active users
		if ( Permissions::isAdmin() ) {
			if ( isset( $params['active'] ) ) {
				$query->where( 'active', $params['active'] );
			}
		} else {
			$query->where( 'active', 1 );
		}

		// Modify query to filter trashed users
		if ( Permissions::isInternalAdmin() ) {
			if ( isset( $params['trashed'] ) ) {
				$query->where( 'trashed', $params['trashed'] );
			}
		} else {
			$query->where( 'trashed', 0 );
		}

		return $query;
	}

	public static function checkIfProvided($params, $keys): bool {

		if (!is_array($keys)) {
			$keys = [$keys];
		}

		foreach($keys as $key) {
			if (!isset($params[$key])) {
				return false;
			}
		}

		return true;
	}

	public static function addWhereClauses($query, $params, $keys = []): object {

		if (!is_array($keys)) {
			$keys = [$keys];
		}

		foreach($keys as $key) {
			if (isset($params[$key])) {
				$query->where($key, $params[$key]);
			}
		}

		return $query;
	}

	public static function updateIfProvided($entity, $params, $keys): void {

		if (!is_array($keys)) {
			$keys = [$keys];
		}

		foreach($keys as $key) {
			if (isset($params[$key])) {
				$entity->$key = $params[$key];
			}
		}
	}

	public static function handleImageUpload($file):string | \WP_Error {
		// Define overrides
		$overrides = array(
			'test_form' => false,
			'mimes' => array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'png' => 'image/png',
				'gif' => 'image/gif'
			)
		);

		// Handle file upload
		$upload = wp_handle_upload($file, $overrides);

		// Check for errors
		if (isset($upload['error'])) {
			return new \WP_Error('upload_failed', $upload['error'], array('status' => 500));
		}

		// File upload successful
		return $upload['file'];
	}

}