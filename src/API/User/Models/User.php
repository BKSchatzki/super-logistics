<?php

namespace BigTB\SL\API\User\Models;

use Illuminate\Database\Eloquent\Model;

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
		return $this->hasMany( 'BigTB\SL\API\User\Models\UserMeta', 'user_id', 'ID' )
		            ->where( 'meta_key', 'wp_capabilities' )
		            ->select( 'user_id', 'meta_value' );
	}

	public function entities(): object {
		return $this->belongsToMany( 'BigTB\SL\API\Entity\Models\Entity', 'sl_entity_users', 'user_id', 'entity_id' );
	}

	public function projects() {
		return $this->belongsToMany( 'BigTB\SL\API\Project\Models\Project', 'pm_role_user', 'user_id', 'project_id' );
	}

	public function tasks() {
		return $this->belongsToMany( 'BigTB\SL\API\Task\Models\Task', 'pm_assignees', 'assigned_to', 'task_id' );
	}

	public function activities() {
		return $this->hasMany( 'BigTB\SL\API\Activity\Models\Carrier', 'actor_id' );
	}

	public function assignees() {
		return $this->hasMany( 'BigTB\SL\API\Common\Models\Assignee', 'assigned_to' );
	}

	public function scopeMultisite( $q ) {
		global $wpdb;

		if ( is_multisite() ) {
			$user_meta_key = pm_user_meta_key();
			$usermeta_tb   = $wpdb->base_prefix . 'usermeta';
			$users_tb      = $wpdb->base_prefix . 'users';

			$q->leftJoin( $usermeta_tb . ' as umeta', 'umeta.user_id', '=', $users_tb . '.ID' )
			  ->where( 'umeta.meta_key', $user_meta_key );
		}
	}
}
