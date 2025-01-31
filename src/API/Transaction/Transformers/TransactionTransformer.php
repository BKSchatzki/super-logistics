<?php


namespace BigTB\SL\API\Transaction\Transformers;

use BigTB\SL\API\Transaction\Models\Transaction;
use BigTB\SL\API\User\Models\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract {

	public function transform( Transaction $item ): array {

		$show = [
			'id'   => $item->show->id,
			'name' => $item->show->entity->name,
		];

		$zone   = self::getObjectData( $item->zone );
		$booth  = self::getObjectData( $item->booth );
		$client = self::getObjectData( $item->show->client );

		$created_by_user = self::getUserData( $item, 'created_by' );
		$updated_by_user = self::getUserData( $item, 'updated_by' );

		$arrivalStatus = self::determineTimeliness( $item );

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
			'image_path'       => $item->image_path,
			'arrival_status'   => $arrivalStatus,
			'active'           => (bool) $item->active,
			'trashed'          => (bool) $item->trashed,
			'created_by'       => $item->created_by,
			'created_by_user'  => $created_by_user,
			'created_at'       => $item->created_at,
			'updated_by'       => $item->updated_by,
			'updated_by_user'  => $updated_by_user,
			'updated_at'       => $item->updated_at,
		];
	}

	private static function determineTimeliness( $item ): string {
		$earliest = $item->show->date_start;
		$latest   = $item->show->date_end;

		if ( $item->created_at < $earliest ) {
			return 'Early';
		} elseif ( $item->created_at > $latest ) {
			return 'Late';
		} else {
			return 'On Time';
		}
	}

	private static function getUserData( $item, $key ): array {
		$user = User::find( $item->$key );

		return [
			'id'   => $user->id,
			'name' => $user->first_name[0]['meta_value'] . ' ' . $user->last_name[0]['meta_value'],
		];
	}

	private static function getObjectData( $object ): array {
		return [
			'id'   => $object->id,
			'name' => $object->name,
		];
	}
}
