<?php

namespace SL\Activity\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\User\Models\User;
use SL\Project\Models\Project;

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
