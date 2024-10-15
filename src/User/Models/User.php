<?php

namespace SL\User\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Role\Models\Role;

class User extends Eloquent {
    protected $primaryKey = 'ID';

    protected $table = 'users';
    protected $hidden = ['user_pass', 'user_activation_key'];

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

    protected $dates = ['user_registered'];

    public function roles() {
        return $this->belongsToMany( 'SL\Role\Models\Role', 'pm_role_user', 'user_id', 'role_id' )
            ->withPivot('project_id', 'role_id');
    }

    public function projects() {
        return $this->belongsToMany( 'SL\Project\Models\Project', 'pm_role_user', 'user_id', 'project_id' );
    }

    public function tasks() {
        return $this->belongsToMany( 'SL\Task\Models\Task', 'pm_assignees','assigned_to', 'task_id' );
    }

    public function activities () {
        return $this->hasMany( 'SL\Activity\Models\Carrier', 'actor_id' );
    }

    public function assignees() {
        return $this->hasMany( 'SL\Common\Models\Assignee', 'assigned_to' );
    }

    public function scopeMultisite( $q ) {
        global $wpdb;

        if ( is_multisite() ) {
            $user_meta_key = pm_user_meta_key();
            $usermeta_tb   = $wpdb->base_prefix . 'usermeta';
            $users_tb      = $wpdb->base_prefix . 'users';
            
            $q->leftJoin( $usermeta_tb . ' as umeta', 'umeta.user_id', '=', $users_tb . '.ID')
                ->where( 'umeta.meta_key', $user_meta_key );
        }
    }
}
