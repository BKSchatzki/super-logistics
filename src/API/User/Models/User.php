<?php

namespace BigTB\SL\API\User\Models;

use BigTB\SL\API\Entity\Models\Entity;
use Illuminate\Database\Eloquent\Model;


class User extends Model {

	protected $primaryKey = 'ID';

	protected $table = 'users';
	protected $hidden = [ 'user_pass', 'user_activation_key' ];
	public $timestamps = false;
	protected array $dates = [ 'user_registered' ];

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

	//----------------------------------------
	// Relationships
	//----------------------------------------

	public function roles(): object {
		global $wpdb;

		return $this->hasMany( UserMeta::class, 'user_id', 'ID' )
		            ->where( 'meta_key', $wpdb->prefix . 'capabilities' )
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

	public function client(): object {
		return $this->belongsToMany( Entity::class, 'sl_entity_users', 'user_id', 'entity_id' )->where( 'type', 1 );
	}

	public function shows(): object {
		return $this->belongsToMany( Entity::class, 'sl_entity_users', 'user_id', 'entity_id' )->where( 'type', 2 );
	}

	public function status(): object {
		return $this->hasOne( UserStatus::class, 'user_id', 'ID' );
	}
}
