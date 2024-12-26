<?php

namespace BigTB\SL\API\User\Models;

use Illuminate\Database\Eloquent\Model;
use BigTB\SL\API\Entity\Models\Entity;
use BigTB\SL\API\User\Models\UserMeta;

class User extends Model {
	protected $primaryKey = 'ID';

	protected $table = 'users';
	protected $hidden = [ 'user_pass', 'user_activation_key' ];

	public $timestamps = false;

	protected $fillable = [
		'user_login',
		'user_nicename',
		'user_email',
		'user_url',
		'user_registered',
		'user_activation_key',
		'user_status',
		'display_name'
	];

	protected $dates = [ 'user_registered' ];

	public function roles(): object {
		return $this->hasMany( UserMeta::class, 'user_id', 'ID' )
		            ->where( 'meta_key', 'wp_capabilities' )
		            ->select( 'user_id', 'meta_value' );
	}

	public function first_name(): object {
		return $this->hasMany( UserMeta::class, 'user_id', 'ID' )
		            ->where( 'meta_key', 'first_name' )
		            ->select( 'user_id', 'meta_value' );
	}

	public function last_name(): object {
		return $this->hasMany( UserMeta::class, 'user_id', 'ID' )
		            ->where( 'meta_key', 'last_name' )
		            ->select( 'user_id', 'meta_value' );
	}

	public function entities(): object {
		return $this->belongsToMany( Entity::class,
			'sl_entity_users', 'user_id', 'entity_id' );
	}
}
