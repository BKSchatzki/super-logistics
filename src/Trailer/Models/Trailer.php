<?php

namespace SL\Trailer\Models;

use SL\Core\DB_Connection\Model as Eloquent;

class Trailer extends Eloquent {
    protected $table = 'sl_trailers';
    protected $fillable = [
        'trailer',
        'carrier_id',
    ];

    public function carrier() {
        return $this->belongsTo('Carrier', 'carrier_id');
    }

    public function shipments() {
        return $this->hasMany('Shipment', 'trailer_id');
    }
}
