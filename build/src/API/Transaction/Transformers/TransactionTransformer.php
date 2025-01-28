<?php


namespace BigTB\SL\API\Transaction\Transformers;

use BigTB\SL\API\Transaction\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract {

	public function transform( Transaction $item ): array {

		$show = [
			'id'   => $item->show->id,
			'name' => $item->show->entity->name,
		];

		$zone = [
			'id'   => $item->zone->id,
			'name' => $item->zone->name,
		];

		$booth = [
			'id'   => $item->booth->id,
			'name' => $item->booth->name,
		];

		$client = $item->show->client;
		$client = [
			'id'   => $client->id,
			'name' => $client->name,
		];

		$created_by_user = \BigTB\SL\Models\User::find( $item->created_by );
		$updated_by_user = \BigTB\SL\Models\User::find( $item->updated_by );

		return [
			'id'               => (int) $item->id,
			'shipper'          => $item->shipper,
			'exhibitor'        => $item->exhibitor,
			'client'           => $client,
			'show_id'          => (int) $item->show_id,
			'show'             => $show,
			'zone_id'          => (int) $item->zone_id,
			'zone'             => $zone,
			'booth_id'         => (int) $item->booth_id,
			'booth'            => $booth,
			'carrier'          => $item->carrier,
			'tracking'         => $item->tracking,
			'street_address'   => $item->street_address,
			'shipper_city'     => $item->shipper_city,
			'shipper_state'    => $item->shipper_state,
			'shipper_zip'      => $item->shipper_zip,
			'freight_type'     => (int) $item->freight_type,
			'crate_pcs'        => (int) $item->crate_pcs,
			'carton_pcs'       => (int) $item->carton_pcs,
			'skid_pcs'         => (int) $item->skid_pcs,
			'fiber_case_pcs'   => (int) $item->fiber_case_pcs,
			'carpet_pcs'       => (int) $item->carpet_pcs,
			'misc_pcs'         => (int) $item->misc_pcs,
			'total_pcs'        => (int) $item->total_pcs,
			'total_weight'     => (int) $item->total_weight,
			'remarks'          => $item->remarks,
			'special_handling' => (bool) $item->special_handling,
			'pallet'           => $item->pallet,
			'trailer'          => $item->trailer,
			'active'           => (bool) $item->active,
			'trashed'          => (bool) $item->trashed,
			'created_by'       => (int) $item->created_by,
			'created_by_user'  => $created_by_user->display_name,
			'created_at'       => $item->created_at,
			'updated_by'       => (int) $item->updated_by,
			'updated_by_user'  => $updated_by_user->display_name,
			'updated_at'       => $item->updated_at,
		];
	}
}
