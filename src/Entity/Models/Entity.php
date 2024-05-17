<?php

namespace SL\Entity\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\User\Models\User;

class Entity extends Eloquent {
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
        'logo_path'
    ];
    public $timestamps = false;

    public function transactions():object {
        return $this->hasMany('Transaction', 'entity_id');
    }

    public function users():object {
        return $this->belongsToMany('User', 'sl_entity_users', 'entity_id', 'user_id');
    }
}
