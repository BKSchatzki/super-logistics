<?php

namespace SL\Show\Models;

use SL\Core\DB_Connection\Model as Eloquent;

class Show extends Eloquent {
    protected $table = 'sl_shows';
    protected $fillable = [
        'name',
        'entity_id',
        'client_id',
        'min_carat_weight',
        'carat_weight_inc',
        'date_start',
        'date_end',
        'date_expiry',
        'floor_plan_path'
    ];

    public $timestamps = false;

    public function places():object {
        return $this->hasMany('SL\Show\Models\ShowPlace', 'show_id');
    }

    public function transactions():object {
        return $this->hasMany('SL\Transaction\Models\Transaction', 'show_id');
    }

    public function entity():object {
        return $this->belongsTo('SL\Entity\Models\Entity', 'entity_id');
    }
}
