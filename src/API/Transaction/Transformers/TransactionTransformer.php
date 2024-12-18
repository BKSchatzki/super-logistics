<?php


namespace BigTB\SL\API\Transaction\Transformers;

use BigTB\SL\API\Transaction\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{

    public function transform(Transaction $item)
    {
        return [
            'id' => (int)$item->id,
            'show_id' => (int)$item->show_id,
            'show_name' => $item->show->entity->name,
            'client_id' => (int)$item->client_id,
            'client_name' => $item->client->name,
            'exhibitor_id' => (int)$item->exhibitor_id,
            'exhibitor_name' => $item->exhibitor->name,
            'carrier_id' => (int)$item->carrier_id,
            'carrier_name' => $item->carrier->name,
            'shipper_id' => (int)$item->shipper_id,
            'shipper_name' => $item->shipper->name,
            'address' => $item->shipper->address ?? null,
            'city' => $item->shipper->city ?? null,
            'state' => $item->shipper->state ?? null,
            'zip' => $item->shipper->zip ?? null,
            'place' => $item->place,
            'booth' => $item->booth,
            'shipment' => $item->shipment,
            'trailer' => $item->trailer,
            'receiver' => $item->receiver,
            'tracking' => $item->tracking,
            'billable_weight' => (int)$item->billable_weight,
            'pallet_no' => $item->pallet_no,
            'freight_type' => $item->freight_type,
            'items' => $item->items ?? [],
            'updates' => $item->updates ?? []
        ];
    }
}
