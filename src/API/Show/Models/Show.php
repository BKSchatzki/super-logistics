<?php

namespace BigTB\SL\API\Show\Models;

use BigTB\SL\API\Core\DB_Connection\Model as Eloquent;

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
        return $this->hasMany('BigTB\SL\API\Show\Models\ShowPlace', 'show_id');
    }

    public function transactions():object {
        return $this->hasMany('BigTB\SL\API\Transaction\Models\Transaction', 'show_id');
    }

    public function entity():object {
        return $this->belongsTo('BigTB\SL\API\Entity\Models\Entity', 'entity_id');
    }
}
