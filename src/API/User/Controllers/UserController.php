<?php

namespace BigTB\SL\API\User\Controllers;

use BigTB\SL\API\User\Models\User;
use BigTB\SL\API\User\Transformers\UserTransformer;
use BigTB\SL\Setup\Controller;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use WP_REST_Request;

// TODO: Error Handling!!
// TODO: Testing!!

class UserController extends Controller {

	public static function get( WP_REST_Request $request ): array {
		$params       = $request->get_params();
		$params['ID'] = $params['id'];
		$users        = self::getUsers( $params );
		if ( isset( $params['roles'] ) ) {
			$roles = $params['roles'];
			$users = $users->filter( function ( $entity ) use ( $roles ) {
				$userRoles = $entity->roles->pluck( 'meta_value' )->toArray();

				return ! empty( array_intersect( $userRoles, $roles ) );
			} );
		}

		$resource = new Collection( $users, new UserTransformer );

		return self::prepareArrayResponse( $resource );
	}

	public static function getCurrent(): array {
		$currentUser = wp_get_current_user();
		$params      = [
			'ID' => $currentUser->ID
		];
		$users       = self::getUsers( $params );
		$resource    = new Item( $users[0], new UserTransformer );

		return self::prepareArrayResponse( $resource );
	}

	public static function create( WP_REST_Request $request ): array {
		// can take first_name, last_name, email, user_login, role, and roles
		$params = $request->get_params();

		$userData = self::createUser( $params );

		if ( ! isset( $userData->ID ) ) {
			return self::prepareErrorResponse( $userData );
		}

		$users = self::getUsers( [ 'ID' => $userData->ID ] );

		$resource = new Item( $users[0], new UserTransformer );

		return self::prepareArrayResponse( $resource );
	}

	public static function update( WP_REST_Request $request ): array {
		$params = $request->get_params();
		$userId = $params['id'];

		$user = User::find( $userId );
		if ( ! $user ) {
			return self::prepareErrorResponse( 'User not found' );
		}

		if ( isset( $params['client'] ) ) {
			$user->entities()->wherePivot( 'user_id', $userId )->sync( $params['client'] );
		}

		if ( isset( $params['shows'] ) ) {
			$user->entities()->wherePivot( 'user_id', $userId )->sync( $params['shows'] );
		}

		self::updateRoles( $userId, $params['roles'] );

		self::updateMetaIfProvided( $userId, $params, [ 'first_name', 'last_name' ] );

		self::updateIfProvided( $user, $params, [ 'email' ] );

		$user->save();

		$users = self::getUsers( [ 'ID' => $userId ] );

		$resource = new Item( $users[0], new UserTransformer );

		return self::prepareArrayResponse( $resource );
	}

	public static function delete( WP_REST_Request $request ): array {
		$params = $request->get_params();
		$userId = $params['id'];

		$user = User::find( $userId );
		if ( ! $user ) {
			return self::prepareErrorResponse( 'User not found' );
		}

		$user->delete();

		$resource = new Item( $user, new UserTransformer );

		return self::prepareArrayResponse( $resource );
	}

	private static function getUsers( $params ) {
		$query = self::addWhereClauses( User::with( [
			'roles',
			'first_name',
			'last_name',
			'entities',
			'entities.show'
		] ), $params, [ 'ID' ] );

		if ( isset( $params['first_name'] ) ) {
			$query->whereHas( 'first_name', function ( $q ) use ( $params ) {
				$q->where( 'meta_value', $params['first_name'] );
			} );
		}

		if ( isset( $params['last_name'] ) ) {
			$query->whereHas( 'last_name', function ( $q ) use ( $params ) {
				$q->where( 'meta_value', $params['last_name'] );
			} );
		}

		return $query->get();
	}

	public static function createUser( $userData ): object {
		error_log( '///// Creating user with data: ' . print_r( $userData, true ) );
		// Set default values for user data if not provided
		$defaults = [
			'user_login' => '',
			'user_pass'  => wp_generate_password(),
			'user_email' => '',
			'role'       => ''
		];

		// Merge provided user data with defaults
		$userData = wp_parse_args( $userData, $defaults );

		// Extract roles if provided
		$roles = $userData['roles'] ?? [ $userData['role'] ];
		unset( $userData['roles'] );

		// Create the user
		$userId = wp_insert_user( $userData );

		// Check for errors
		if ( is_wp_error( $userId ) ) {
			return $userId; // Return the error
		}

		// Assign multiple roles
		foreach ( $roles as $role ) {
			add_user_meta( $userId, 'wp_capabilities', [ $role => true ], true );
		}

		// Check for errors
		if ( is_wp_error( $userId ) ) {
			return $userId; // Return the error
		}

		// Optionally, send a notification to the new user
		wp_send_new_user_notifications( $userId );

		// Return the created user object
		return get_userdata( $userId );
	}

	public static function updateMetaIfProvided( $uID, $params, $keys ): void {
		if ( ! is_array( $keys ) ) {
			$keys = [ $keys ];
		}

		foreach ( $keys as $key ) {
			if ( isset( $params[ $key ] ) ) {
				update_user_meta( $uID, $key, $params[ $key ] );
			}
		}
	}

	public static function updateRoles( $uID, $roles ): void {
		$user = new \WP_User( $uID );

		// Remove all existing roles
		foreach ( $user->roles as $role ) {
			$user->remove_role( $role );
		}

		// Add new roles
		foreach ( $roles as $role ) {
			$user->add_role( $role );
		}
	}

}
