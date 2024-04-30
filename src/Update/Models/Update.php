<?php

namespace SL\Activity\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\User\Models\User;
use SL\Project\Models\Project;

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
