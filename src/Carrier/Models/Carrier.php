<?php

namespace SL\Carrier\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\User\Models\User;
use SL\Project\Models\Project;


class Carrier extends Eloquent {
    protected $table = 'sl_entities';
    protected $fillable = [
        'name',
        'logo_path',
        'type',
    ];

    public function transactions() {
        return $this->hasMany('Transaction', 'exhibitor_id');
    }

    public function trailers() {
        return $this->hasMany('Trailer', 'carrier_id');
    }
}
