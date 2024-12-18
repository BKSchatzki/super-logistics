<?php


namespace BigTB\SL\API\Entity\Transformers;

use BigTB\SL\API\Entity\Models\Entity;
use League\Fractal\TransformerAbstract;

class EntityTransformer extends TransformerAbstract
{
    public function transform(Entity $item)
    {
        $codes = $item->codes->map(function ($code) {
            return [
                'id' => $code->id,
                'show_id' => $code->pivot->show_id,
                'code' => $code->pivot->code,
                'show_name' => $code->entity->name,
            ];
        });

        return [
            'id' => (int)$item->id,
            'name' => $item->name,
            'type' => $item->type,
            'phone' => $item->phone,
            'email' => $item->email,
            'address' => $item->address,
            'city' => $item->city,
            'state' => $item->state,
            'zip' => $item->zip,
            'logo_path' => $item->logo_path,
            'codes' => $codes,
        ];
    }
}
