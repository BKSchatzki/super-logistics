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
        'image_path',
        'note'
    ];
    public $timestamps = false;

    public function transaction(): object {
        return $this->belongsTo('Transaction', 'transaction_id');
    }

    public function user(): object {
        return $this->belongsTo('User', 'user_id');
    }
}
