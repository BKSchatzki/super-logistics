<?php

namespace SL\Show\Models;

use SL\Core\DB_Connection\Model as Eloquent;

class Show extends Eloquent {
    protected $table = 'sl_shows';
    protected $fillable = [
        'name',
        'logo_path',
        'address',
        'city',
        'state',
        'zip',
        'floor_plan_path'
    ];

    public function transactions() {
        return $this->hasMany('Transaction', 'show_id');
    }
}
