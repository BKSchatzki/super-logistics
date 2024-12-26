<?php

namespace BigTB\SL\API\User\Transformers;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {
	public function transform( $item ) {
		$firstName = $item->first_name ? $item->first_name[0]['meta_value'] : '';
		$lastName = $item->last_name ? $item->last_name[0]['meta_value'] : '';
		$rolesData = unserialize($item->roles[0]['meta_value']);
		$roles = [];
		foreach($rolesData as $role => $value) {
			if ($value) {
				$roles[] = $role;
			}
		}
		$admin = !empty(array_intersect(['administrator', 'internal_admin', 'client_admin'], $roles));
		$client = [];
		$shows = [];
		foreach ($item->entities as $entity) {
			if ($entity->type == 2) {
				$shows[] = [
					'id' => $entity->id,
					'name' => $entity->name,
					'address' => $entity->address,
					'city' => $entity->city,
					'state' => $entity->state,
					'zip' => $entity->zip,
					'phone' => $entity->phone,
					'email' => $entity->email,
					'logo_path' => $entity->logo_path,
					'min_carat_weight' => $entity->show->min_carat_weight,
					'carat_weight_inc' => $entity->show->carat_weight_inc,
					'date_start' => $entity->show->date_start,
					'date_end' => $entity->show->date_end,
					'floor_plan_path' => $entity->show->floor_plan_path,
					'active' => $entity->show->active,
				];
			} elseif ($entity->type == 1) {
				$client = [
					"id" => $entity->id,
					"name" => $entity->name
				];
			}
		}

		return [
			'id' => (int)$item->ID,
			'name' => (string)$item->display_name,
			'first_name' => (string)$firstName,
			'last_name' => (string)$lastName,
			'roles' => (array)$roles,
			'admin' => (boolean)$admin,
			'client' => (array)$client,
			'shows' => (array)$shows,
			'email' => (string)$item->user_email,
		];
	}
}
