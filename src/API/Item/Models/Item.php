<?php

namespace BigTB\SL\API\Item\Models;

use BigTB\SL\API\Core\DB_Connection\Model as Eloquent;
use BigTB\SL\API\User\Models\User;

class Item extends Eloquent {
    protected $table = 'sl_items';
    protected $fillable = [
        'transaction_id',
        'type',
        'pcs',
        'bol_count',
        'weight',
        'notes',
        'tracking',
        ];
    public $timestamps = false;

    public function transaction() {
        return $this->belongsTo( 'BigTB\SL\API\Transaction\Models\Transaction' );
    }
}
