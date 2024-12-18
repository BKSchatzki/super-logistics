<?php

namespace BigTB\SL\API\Show\Models;

use Illuminate\Database\Eloquent\Model;

class ShowPlace extends Model {
    protected $table = 'sl_show_places';
    protected $fillable = [
        'show_id',
        'type',
        'name'
    ];
    public $timestamps = false;

    public function shows(): object {
        return $this->belongsTo('BigTB\SL\API\Show\Models\Show', 'show_id');
    }

    public function zoneTrans(): object {
        return $this->hasMany('BigTB\SL\API\Transaction\Models\Transaction', 'zone');
    }

    public function colorTrans(): object {
        return $this->hasMany('BigTB\SL\API\Transaction\Models\Transaction', 'color');
    }
}
