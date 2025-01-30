<?php

namespace BigTB\SL\Setup\Core;

use BigTB\SL\API\User\Models\User;
use BigTB\SL\Setup\Routing\Permissions;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use WP_Error;

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

		if (is_string($file)) {
			return self::handleStringImage($file, 'image');
		}

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
			error_log('Error uploading file: ' . $upload['error']);
			return new \WP_Error('upload_failed', $upload['error'], array('status' => 500));
		}

		// File upload successful
		return $upload['url'];
	}

	public static function handleStringImage( $base64_img, $title ) {

		// Upload dir.
		$upload_dir  = wp_upload_dir();

		$img             = str_replace( 'data:image/png;base64,', '', $base64_img );
		$img             = str_replace( ' ', '+', $img );
		$decoded         = base64_decode( $img );
		$filename        = $title . '.png';
		$file_type       = 'image/png';
		$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

		// Save the image in the uploads directory.
		$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

		$attachment = array(
			'post_mime_type' => $file_type,
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
		);

		$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );

		return wp_get_attachment_url( $attach_id );
	}

	protected static function getCurrentUserModel(): User {
		return User::where( 'id', wp_get_current_user()->ID )->with( 'client', 'shows' )->first();
	}

}