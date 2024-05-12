<?php

namespace SL\Shipment\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\User\Models\User;

class Shipment extends Eloquent {
    protected $table = 'sl_shipments';
    protected $fillable = [
        'transaction_id',
        'trailer_id',
        'total_pcs',
        'note',
        'pallet_no',
        'date_received'
    ];

    public function transaction() {
        return $this->belongsTo('Transaction', 'transaction_id');
    }

    public function trailer() {
        return $this->belongsTo('Trailer', 'trailer_id');
    }
}
