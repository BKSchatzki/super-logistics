<?php

namespace SL\Show\Models;

use SL\Core\DB_Connection\Model as Eloquent;

class ShowPlace extends Eloquent {
    protected $table = 'sl_show_places';
    protected $fillable = [
        'show_id',
        'type',
        'name'
    ];
    public $timestamps = false;

    public function shows(): object {
        return $this->belongsTo('SL\Show\Models\Show', 'show_id');
    }

    public function zoneTrans(): object {
        return $this->hasMany('SL\Transaction\Models\Transaction', 'zone');
    }

    public function colorTrans(): object {
        return $this->hasMany('SL\Transaction\Models\Transaction', 'color');
    }
}
