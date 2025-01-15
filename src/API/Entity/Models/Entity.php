<?php

namespace BigTB\SL\API\Entity\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model {
	// Clients are type 1,
	// Shows are type 2, and
	// Carriers are type 3
	protected $table = 'sl_entities';
	protected $fillable = [
		'name',
		'type',
		'phone',
		'email',
		'address',
		'city',
		'state',
		'zip',
		'logo_path',
		'active',
	];
	public $timestamps = false;

	public function transactions(): object {
		return $this->hasMany( 'BigTB\SL\API\Transaction\Models\Transaction', 'client_id' );
	}

	public function users(): object {
		return $this->belongsToMany( 'BigTB\SL\API\User\Models\User', 'sl_entity_users', 'entity_id', 'user_id', 'id', 'ID' );
	}

	public function show(): object {
		return $this->hasOne( 'BigTB\SL\API\Entity\Models\Show', 'entity_id' );
	}
}
