<?php

namespace BigTB\SL\API\User\Transformers;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {
	public function transform( $item ): array {
		$firstName = $item->first_name ? $item->first_name[0]['meta_value'] : '';
		$lastName  = $item->last_name ? $item->last_name[0]['meta_value'] : '';
		$fullName  = $firstName . ' ' . $lastName;
		list( $role, $isAdmin, $isInternalAdmin, $isClientAdmin, $isInternal, $isClient ) = self::formatRoles( $item );
		list( $shows, $client ) = self::formatEntities( $item );

		return [
			'id'              => (int) $item->ID,
			'name'            => $fullName,
			'user_login'      => (string) $item->user_login,
			'active'          => (boolean) $item->status->active,
			'trashed'         => (boolean) $item->status->trashed,
			'first_name'      => (string) $firstName,
			'last_name'       => (string) $lastName,
			'role'            => (string) $role,
			'nice_role'       => (string) self::toCapitalCase( $role ),
			'isAdmin'         => (boolean) $isAdmin,
			'isInternalAdmin' => (boolean) $isInternalAdmin,
			'isClientAdmin'   => (boolean) $isClientAdmin,
			'isInternal'      => (boolean) $isInternal,
			'isClient'        => (boolean) $isClient,
			'client'          => $client,
			'shows'           => (array) $shows,
			'user_email'      => (string) $item->user_email,
		];
	}

	private static function formatRoles( $item ): array {

		if ( empty( $item->roles || ! is_array( $item->roles ) ) ) {
			return [ '', false, false, false, false, false ];
		}
		$rolesData = isset( $item->roles[0] ) ? unserialize( $item->roles[0]['meta_value'] ) : [];
		$roles     = [];
		foreach ( $rolesData as $role => $value ) {
			if ( $value ) {
				$roles[] = $role;
			}
		}

		$rolePriority = [
			'administrator',
			'internal_admin',
			'client_admin',
			'internal_employee',
			'client_employee'
		];

		$role = '';
		foreach ( $rolePriority as $priorityRole ) {
			if ( in_array( $priorityRole, $roles ) ) {
				$role = $priorityRole;
				break;
			}
		}

		if ( empty( $role ) && ! empty( $roles ) ) {
			$role = $roles[0];
		}

		$isAdmin         = ! empty( array_intersect( [ 'administrator', 'internal_admin', 'client_admin' ], $roles ) );
		$isInternalAdmin = in_array( 'internal_admin', $roles );
		$isClientAdmin   = in_array( 'client_admin', $roles );
		$isInternal      = ! empty( array_intersect( [
			'administrator',
			'internal_admin',
			'internal_employee'
		], $roles ) );
		$isClient        = ! empty( array_intersect( [ 'client_admin', 'client_employee' ], $roles ) );

		return [ $role, $isAdmin, $isInternalAdmin, $isClientAdmin, $isInternal, $isClient ];
	}

	private static function formatEntities( $item ): array {
		$shows  = [];
		$client = null;
		foreach ( $item->entities as $entity ) {
			if ( $entity->type == 2 ) {
				$shows[] = [
					'id'               => $entity->id,
					'name'             => $entity->name,
					'address'          => $entity->address,
					'city'             => $entity->city,
					'state'            => $entity->state,
					'zip'              => $entity->zip,
					'phone'            => $entity->phone,
					'email'            => $entity->email,
					'logo_path'        => $entity->logo_path,
					'min_carat_weight' => $entity->show->min_carat_weight,
					'carat_weight_inc' => $entity->show->carat_weight_inc,
					'date_start'       => $entity->show->date_start,
					'date_end'         => $entity->show->date_end,
					'floor_plan_path'  => $entity->show->floor_plan_path,
					'active'           => $entity->show->active,
				];
			} elseif ( $entity->type == 1 ) {
				$client = [
					"id"   => $entity->id,
					"name" => $entity->name
				];
			}
		}

		return [ $shows, $client ];
	}

	private static function toCapitalCase( $str ): string {
		$str = str_replace( '_', ' ', $str );

		return ucwords( $str );
	}
}
