<?php

namespace BigTB\SL\Setup\Core;

class Controller {

	use ResponseManager;

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