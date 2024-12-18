<?php

namespace BigTB\SL\API\User\Models;

use Illuminate\Database\Eloquent\Model;
use BigTB\SL\API\Role\Models\Role;

class User_Role extends Model {
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
        return $this->hasOne( 'BigTB\SL\API\Role\Models\Role', 'id', 'role_id' );
    }

}
