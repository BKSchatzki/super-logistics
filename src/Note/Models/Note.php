<?php

namespace SL\Activity\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\User\Models\User;
use SL\Project\Models\Project;

class Note extends Eloquent {
    protected $table = 'sl_notes';
    protected $fillable = [
        'user_id',
        'transaction_id',
        'note',
        'datetime'
    ];

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

    public function transaction() {
        return $this->belongsTo('Transaction', 'transaction_id');
    }
}
