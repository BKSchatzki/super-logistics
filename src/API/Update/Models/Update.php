<?php

namespace BigTB\SL\API\Update\Models;

use BigTB\SL\API\Core\DB_Connection\Model as Eloquent;

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
        return $this->belongsTo('BigTB\SL\API\Transaction\Models\Transaction', 'transaction_id');
    }

    public function user(): object {
        return $this->belongsTo('\BigTB\SL\API\User\Models\User', 'user_id');
    }
}
