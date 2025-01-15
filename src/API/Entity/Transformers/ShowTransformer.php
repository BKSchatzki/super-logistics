<?php


namespace BigTB\SL\API\Entity\Transformers;

use BigTB\SL\API\Entity\Models\Show;
use League\Fractal\TransformerAbstract;

class ShowTransformer extends TransformerAbstract
{

    public function transform(Show $item)
    {
		$client = [
			'id' => $item->client->id,
			'name' => $item->client->name,
			];

        return [
            'id' => (int)$item->id,
            'client' => (array) $client,
            'name' => (string) $item->entity->name,
            'date_start' => $item->date_start,
            'date_end' => $item->date_end ?? '',
            'min_carat_weight' => (int) $item->min_carat_weight,
            'carat_weight_inc' => (int) $item->carat_weight_inc,
            'address' => (string) $item->entity->address ?? '',
            'city' => (string) $item->entity->city ?? '',
            'state' => (string) $item->entity->state ?? '',
            'zip' => (string) $item->entity->zip ?? '',
            'active' => (boolean) $item->entity->active ?? '',
            'logo_path' => (string) $item->entity->logo_path ?? '',
            'floor_plan_path' => (string) $item->floor_plan_path ?? '',
        ];
    }
}
