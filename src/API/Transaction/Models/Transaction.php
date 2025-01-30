<?php

namespace BigTB\SL\API\Transaction\Models;

use BigTB\SL\API\Entity\Models\Entity;
use BigTB\SL\API\Entity\Models\Show;
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
		'booth_id',
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
		return $this->hasOneThrough(
			Show::class,
			Entity::class,
			'id', // Foreign key on the entities table...
			'entity_id', // Foreign key on the shows table...
			'show_id', // Local key on the transactions table...
			'id' // Local key on the entities table...
		);
	}

	public function zone(): object {
		return $this->belongsTo( ShowPlace::class, 'zone_id' );
	}

	public function booth(): object {
		return $this->belongsTo( ShowPlace::class, 'booth_id' );
	}

}
