<?php

namespace SL\User\Transformers;

use League\Fractal\TransformerAbstract;

class WPUser_Transformer extends TransformerAbstract
{
    public function transform($item)
    {
        return get_object_vars($item);
    }
}
