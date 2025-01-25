<?php


namespace BigTB\SL\API\Entity\Transformers;

use BigTB\SL\API\Entity\Models\Entity;
use League\Fractal\TransformerAbstract;

class EntityTransformer extends TransformerAbstract {
	public function transform( Entity $item ) {
		return [
			'id'        => (int) $item->id,
			'name'      => (string) $item->name,
			'type'      => (int) $item->type,
			'phone'     => (string) $item->phone,
			'email'     => (string) $item->email,
			'address'   => (string) $item->address,
			'city'      => (string) $item->city,
			'state'     => (string) $item->state,
			'zip'       => (int) $item->zip,
			'logo_path' => (string) $item->logo_path,
			'active'    => (boolean) $item->active,
			'trashed'   => (boolean) $item->trashed
		];
	}
}
