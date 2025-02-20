<?php

namespace BigTB\SL\API\User\Controllers;

use BigTB\SL\API\User\Models\User;
use BigTB\SL\API\User\Transformers\UserTransformer;
use BigTB\SL\Setup\Core\Controller;
use BigTB\SL\Setup\Routing\Permissions;
use League\Fractal\Resource\Item;
use WP_REST_Request;

class UserController extends Controller {

	public static function get( WP_REST_Request $request ): array {

		// Get users
		$params       = $request->get_params();
		$params['ID'] = $params['id'] ?? null;
		$users        = self::getUsers( $params );

		// Filter by roles
		if ( isset( $params['roles'] ) ) {
			$roles = $params['roles'];
			$users = $users->filter( function ( $entity ) use ( $roles ) {
				$userRoles = $entity->roles->pluck( 'meta_value' )->toArray();

				return ! empty( array_intersect( $userRoles, $roles ) );
			} );
		}

		// Filter for Client
		$currentUser = self::getCurrentUserModel();
		if ( Permissions::isClientAdmin() && ! Permissions::isWPAdmin() ) {
			$users = $users->filter( function ( $user ) use ( $currentUser ) {
				return $user->client[0]->id == $currentUser->client[0]->id;
			} );
		}

		return self::collectionResponse( $users, new UserTransformer );
	}

	public static function getCurrent(): array {
		$currentUser = wp_get_current_user();
		$params      = [
			'ID' => $currentUser->ID
		];
		list( $user ) = self::getUsers( $params );

		return self::singleResponse( $user, new UserTransformer );
	}

	public static function logout(): void {
		wp_logout();
	}

	public static function create( WP_REST_Request $request ): array {
		// can take first_name, last_name, email, user_login, role, and roles
		$params = $request->get_params();

		$userData = self::createWPUser( $params );

		if ( ! isset( $userData->ID ) ) {
			self::sendErrorResponse( $userData );
		}
		$user = User::find( $userData->ID );

		if ( str_contains( $params['role'], 'client' ) ) {
			$user = self::associateEntities( $user, $params );
		}

		return self::singleResponse( $user, new UserTransformer );
	}

	public static function update( WP_REST_Request $request ): array {
		$params = $request->get_params();
		$userId = $params['id'];

		$user = User::find( $userId );
		if ( ! $user ) {
			return self::prepareUserNotFoundResponse();
		}

		// Update Roles
		if ( isset( $params['role'] ) ) {
			self::updateRole( $userId, $params['role'] );

			$user = User::find( $userId );
		}

		// Update first_name, last_name, and user_email using WordPress APIs
		if ( isset( $params['first_name'] ) ) {
			update_user_meta( $userId, 'first_name', $params['first_name'] );
		}

		if ( isset( $params['last_name'] ) ) {
			update_user_meta( $userId, 'last_name', $params['last_name'] );
		}

		if ( isset( $params['user_email'] ) ) {
			$user->user_email = $params['user_email'];
		}

		if ( str_contains( $params['role'], 'internal' ) ) {
			$user = self::clearEntities( $user );
		} else {
			// Update entity associations
			$entities = [];

			if ( isset( $params['client_id'] ) ) {
				$entities[] = (int) $params['client_id'];
			}

			if ( isset( $params['shows'] ) ) {
				$entities = array_merge( $entities, array_map( 'intval', (array) $params['shows'] ) );
			}

			if ( ! empty( $entities ) ) {
				$user->entities()->sync( $entities );
			}

		}

		$user->save();

		$resource = new Item( $user, new UserTransformer );

		return self::prepareArrayResponse( $resource );
	}

	public static function delete( WP_REST_Request $request ): array {
		$params = $request->get_params();
		$userId = $params['id'];

		$user = User::find( $userId );
		if ( ! $user ) {
			return self::prepareUserNotFoundResponse();
		}

		$user->status->trashed = 1;
		$user->status->save();

		return self::singleResponse( $user, new UserTransformer );
	}

	public static function restore( WP_REST_Request $request ): array {
		$params = $request->get_params();
		$userId = $params['id'];

		$user = User::find( $userId );
		if ( ! $user ) {
			return self::prepareUserNotFoundResponse();
		}

		$user->status->trashed = 0;
		$user->status->save();

		return self::singleResponse( $user, new UserTransformer );
	}

	public static function markInactive( WP_REST_Request $request ): array {
		$params = $request->get_params();
		$userId = $params['id'];

		$user = User::find( $userId );
		if ( ! $user ) {
			return self::prepareUserNotFoundResponse();
		}

		$user->status->active = 0;
		$user->status->save();

		return self::singleResponse( $user, new UserTransformer );
	}

	public static function markActive( WP_REST_Request $request ): array {
		$params = $request->get_params();
		$userId = $params['id'];

		$user = User::find( $userId );
		if ( ! $user ) {
			return self::prepareUserNotFoundResponse();
		}

		$user->status->active = 1;
		$user->status->save();

		return self::singleResponse( $user, new UserTransformer );
	}

	private static function getUsers( $params ) {

		$allowedRoles = [ 'internal_admin', 'internal_employee', 'client_admin', 'client_employee' ];

		if ( Permissions::isClientAdmin() ) {
			$allowedRoles = [ 'client_admin', 'client_employee' ];
		}

		if ( isset( $params['roles'] ) ) {
			$params['roles'] = array_intersect( $params['roles'], $allowedRoles );
		}

		// If ID is set
		$query = self::addWhereClauses( User::with( [
			'roles',
			'first_name',
			'last_name',
			'entities',
			'entities.show'
		] ), $params, [ 'ID' ] );

		// Filter by first and last name
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

		// View active users
		if ( Permissions::isAdmin() ) {
			if ( isset( $params['active'] ) ) {
				$query->whereHas( 'status', function ( $q ) use ( $params ) {
					$q->where( 'active', $params['active'] );
				} );
			}
		} else {
			$query->whereHas( 'status', function ( $q ) {
				$q->where( 'active', 1 );
			} );
		}

		// View trashed users
		if ( Permissions::isInternalAdmin() ) {
			if ( isset( $params['trashed'] ) ) {
				$query->whereHas( 'status', function ( $q ) use ( $params ) {
					$q->where( 'trashed', $params['trashed'] );
				} );
			}
		} else {
			$query->whereHas( 'status', function ( $q ) {
				$q->where( 'trashed', 0 );
			} );
		}

		return $query->get();
	}

	public static function createWPUser( $userData ): object {
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

	private static function clearEntities( $user ): User {
		$user->entities()->detach();
		$user->save();

		return $user;
	}

	private static function associateEntities( $user, $params ): User {
		// Associate Clients

		if ( isset( $params['client_id'] ) ) {
			$user->entities()->attach( (int) $params['client_id'] );
		}

		// Associate Shows
		if ( isset( $params['shows'] ) ) {
			if ( is_string( $params['shows'] ) ) {
				$params['shows'] = json_decode( $params['shows'], true );
			}
			foreach ( $params['shows'] as $show ) {
				$user->entities()->attach( (int) $show );
			}
		}

		$user->save();

		return $user;
	}

	public static function updateRole( $uID, $role ): void {
		// None of the WordPress methods for users, set_role, add_role, remove_role, etc. work for updating roles
        // So we have to do it manually

        global $wpdb;

        $serializedRole = serialize( [ $role => true ] );
        $capabilities = $wpdb->prefix . 'capabilities';

        error_log("Updating user $uID with role $role and serialized role $serializedRole at meta key $capabilities");


        $wpdb->update(
            $wpdb->usermeta,
            [ 'meta_value' => $serializedRole ],
            [ 'user_id' => $uID, 'meta_key' => "$capabilities" ]
        );

	}

}
