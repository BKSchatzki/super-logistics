<?php


namespace SL\PDF\Transformers;
use League\Fractal\TransformerAbstract;

class LabelTransformer extends TransformerAbstract
{
    public function transform($item)
    {
        return [
            'pdf' => $item['pdf'],
        ];
    }
}
