<?php


namespace SL\PDF\Transformers;
use League\Fractal\TransformerAbstract;

class PDFTransformer extends TransformerAbstract
{
    public function transform($item)
    {
        return [
            'pdf' => $item['pdf'],
        ];
    }
}
