<?php


namespace SL\Transaction\Transformers;

use SL\Transaction\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{

    public function transform(Transaction $item)
    {
        return [
            'id' => (int)$item->id,
            'exhibitor_id' => (int)$item->exhibitor_id,
            'carrier_id' => (int)$item->carrier_id,
            'shipper_id' => (int)$item->shipper_id,
            'show_id' => (int)$item->show_id,
            'zone' => $item->zone,
            'color' => $item->color,
            'billable_weight' => (int)$item->billable_weight,
            'pallet_no' => $item->pallet_no,
            'freight_type' => $item->freight_type
        ];
    }
}
