<?php

namespace SL\Exhibitor\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\User\Models\User;

class Exhibitor extends Eloquent {
    protected $table = 'sl_entities';
    protected $fillable = [
        'name',
        'logo_path',
        'type'
    ];

    public function transactions() {
        return $this->hasMany('Transaction', 'exhibitor_id');
    }

    public function users() {
        return $this->belongsToMany('User', 'sl_exhibitor_users', 'exhibitor_id', 'user_id');
    }
}
