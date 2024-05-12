<?php

namespace SL\Update\Models;

use SL\Core\DB_Connection\Model as Eloquent;

class Update extends Eloquent {
    protected $table = 'sl_updates';
    protected $fillable = [
        'transaction_id',
        'user_id',
        'type',
        'datetime',
        'image_path'
    ];

    public function transaction() {
        return $this->belongsTo('Transaction', 'transaction_id');
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
}
