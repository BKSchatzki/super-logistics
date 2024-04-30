<?php

namespace SL\Activity\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\User\Models\User;
use SL\Project\Models\Project;

class Transaction extends Eloquent {
    protected $table = 'sl_transactions';
    protected $fillable = [
        'exhibitor_id',
        'carrier_id',
        'show_id',
        'zone',
        'color',
        'billable_weight',
        'special',
        'city',
        'state',
        'zip',
        'pallet_no',
        'freight_type'
    ];

    public function exhibitor() {
        return $this->belongsTo('Entity', 'exhibitor_id');
    }

    public function carrier() {
        return $this->belongsTo('Entity', 'carrier_id');
    }

    public function show() {
        return $this->belongsTo('Show', 'show_id');
    }

    public function shipments() {
        return $this->hasMany('Shipment', 'transaction_id');
    }

    public function notes() {
        return $this->hasMany('Note', 'transaction_id');
    }
}
