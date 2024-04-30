<?php

namespace SL\User\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Role\Models\Role;

class User_Role extends Eloquent {
    protected $table = 'pm_role_user';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'role_id',
        'project_id',
        'assigned_by',
    ];

    public static function boot() {
        parent::boot();

        static::saving( function ( $model ) {
            $model->assigned_by = 1;
        } );

        static::updating( function ( $model ) {
            $model->assigned_by = 1;
        } );
    }

    function role() {
        return $this->hasOne( 'SL\Role\Models\Role', 'id', 'role_id' );
    }

}
