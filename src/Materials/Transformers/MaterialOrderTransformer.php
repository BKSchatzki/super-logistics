<?php

namespace SL\Materials\Transformers;

use League\Fractal\TransformerAbstract;
use SL\Common\Traits\Resource_Editors;
use SL\Materials\Models\MaterialOrder;

class MaterialOrderTransformer extends TransformerAbstract {

    use Resource_Editors;

    public function transform( MaterialOrder $item ) {
        return [
            'id'                => $item->id,
            'vendor_id'   => $item->vendor_id,
            'cost'            => $item->cost,
            'title'             => $item->title,
            'description' => $item->description,
            'projects'      => $item->projects,
            'date'            => $item->date,
            'ordered_by' => $item->ordered_by,
        ];
    }
}
