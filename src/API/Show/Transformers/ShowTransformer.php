<?php


namespace BigTB\SL\API\Show\Transformers;

use BigTB\SL\API\Show\Models\Show;
use League\Fractal\TransformerAbstract;

class ShowTransformer extends TransformerAbstract
{

    public function transform(Show $item)
    {
        return [
            'id' => (int)$item->id,
            'client_id' => (int)$item->client_id,
            'name' => $item->entity->name,
            'date_start' => $item->date_start,
            'min_carat_weight' => $item->min_carat_weight,
            'carat_weight_inc' => $item->carat_weight_inc,
            'date_end' => $item->date_end ?? '',
            'date_expiry' => $item->date_expiry ?? '',
            'places' => $item->places->map(function($place) {
                return [
                    'id' => (int)$place->id,
                    'name' => $place->name,
                    'type' => $place->type
                ];
            }),
            'address' => $item->entity->address ?? '',
            'city' => $item->entity->city ?? '',
            'state' => $item->entity->state ?? '',
            'zip' => $item->entity->zip ?? '',
            'logo_path' => $item->entity->logo_path ?? '',
            'floor_plan_path' => $item->floor_plan_path ?? '',
        ];
    }
}
