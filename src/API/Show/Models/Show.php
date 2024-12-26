<?php

namespace BigTB\SL\API\Show\Models;

use Illuminate\Database\Eloquent\Model;

class Show extends Model {
    protected $table = 'sl_shows';
    protected $fillable = [
        'entity_id',
        'client_id',
        'min_carat_weight',
        'carat_weight_inc',
        'date_start',
        'date_end',
        'floor_plan_path',
    ];

    public $timestamps = false;

    public function transactions():object {
        return $this->hasMany('BigTB\SL\API\Transaction\Models\Transaction', 'show_id');
    }

    public function entity():object {
        return $this->belongsTo('BigTB\SL\API\Entity\Models\Entity', 'entity_id');
    }
}
