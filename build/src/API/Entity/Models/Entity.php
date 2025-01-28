<?php

namespace BigTB\SL\API\Entity\Models;

use Illuminate\Database\Eloquent\Model;

use BigTB\SL\API\Transaction\Models\Transaction;
use BigTB\SL\API\User\Models\User;

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
		'trashed'
	];
	public $timestamps = false;

	public function transactions(): object {
		return $this->hasMany( Transaction::class, 'client_id' );
	}

	public function users(): object {
		return $this->belongsToMany( User::class, 'sl_entity_users', 'entity_id', 'user_id', 'id', 'ID' );
	}

	public function show(): object {
		return $this->hasOne( Show::class, 'entity_id' );
	}
}
