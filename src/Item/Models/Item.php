<?php

namespace SL\Item\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\User\Models\User;

class Item extends Eloquent {
    protected $table = 'sl_items';
    protected $fillable = [
        'transaction_id',
        'type',
        'pcs',
        'bol_count',
        'total_weight',
        'notes'
    ];
}
