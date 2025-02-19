<?php

namespace BigTB\SL\API\Transaction\Models;

use BigTB\SL\API\Entity\Models\Entity;
use BigTB\SL\API\Entity\Models\ShowPlace;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
	protected $table = 'sl_transactions';
	protected $fillable = [
		'show_id',
		'active',
		'trashed',
		'created_at',
		'created_by',
		'updated_at',
		'updated_by',
		'shipper',
		'exhibitor',
		'carrier',
		'tracking',
		'zone_id',
		'booth',
		'crate_pcs',
		'carton_pcs',
		'skid_pcs',
		'fiber_case_pcs',
		'carpet_pcs',
		'misc_pcs',
		'total_pcs',
		'total_weight',
		'remarks',
		'trailer',
		'special_handling',
		'shipper_city',
		'shipper_state',
		'shipper_zip',
		'pallet',
		'freight_type',
		'image_path'
	];
	public $timestamps = true;

	public function show(): object {
		return $this->belongsTo( Entity::class, 'show_id' )->with( 'show' );
	}

	public function zone(): object {
		return $this->belongsTo( ShowPlace::class, 'zone_id' );
	}

}
