<?php


namespace BigTB\SL\API\Entity\Transformers;

use BigTB\SL\API\Entity\Models\Show;
use League\Fractal\TransformerAbstract;
use BigTB\SL\Setup\Routing\Permissions;

class ShowTransformer extends TransformerAbstract {

	public function transform( Show $item ): array {
		$client = [
			'id'   => $item->client->id,
			'name' => $item->client->name,
		];

		$zones = $item->zones->filter(fn($zone) => $zone->trashed == 0);
		$booths = $item->booths->filter(fn($zone) => $zone->trashed == 0);

		return [
			'id'               => (int) $item->entity->id,
			'client'           => $client,
			'name'             => (string) $item->entity->name,
			'date_start'       => $item->date_start,
			'date_end'         => $item->date_end ?? '',
			'min_carat_weight' => (int) $item->min_carat_weight,
			'carat_weight_inc' => (int) $item->carat_weight_inc,
			'zones'            => $zones,
			'booths'           => $booths,
			'address'          => (string) $item->entity->address ?? '',
			'city'             => (string) $item->entity->city ?? '',
			'state'            => (string) $item->entity->state ?? '',
			'zip'              => (string) $item->entity->zip ?? '',
			'active'           => (boolean) $item->entity->active ?? '',
			'trashed'          => (boolean) $item->entity->trashed ?? '',
			'logo_path'        => (string) $item->entity->logo_path ?? '',
			'floor_plan_path'  => (string) $item->floor_plan_path ?? '',
		];
	}
}
