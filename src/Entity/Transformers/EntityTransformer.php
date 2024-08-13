<?php


namespace SL\Entity\Transformers;

use SL\Entity\Models\Entity;
use League\Fractal\TransformerAbstract;

class EntityTransformer extends TransformerAbstract
{

    public function transform(Entity $item)
    {
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
            'code' => $item->code,
            'logo_path' => $item->logo_path
        ];
    }
}
