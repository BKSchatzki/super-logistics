<?php

namespace SL\Transaction\Models;

use SL\Core\DB_Connection\Model as Eloquent;

class Transaction extends Eloquent
{
    protected $table = 'sl_transactions';
    protected $fillable = [
        'id',
        'show_id',
        'client_id',
        'carrier_id',
        'shipper_id',
        'exhibitor_id',
        'shipment',
        'zone',
        'color',
        'billable_weight',
        'pallet_no',
        'freight_type'
    ];
    public $timestamps = false;

    public function show(): object
    {
        return $this->belongsTo('Show', 'show_id');
    }

    public function client(): object
    {
        return $this->belongsTo('Entity', 'client_id');
    }

    public function carrier(): object
    {
        return $this->belongsTo('Entity', 'carrier_id');
    }

    public function shipper(): object
    {
        return $this->belongsTo('Entity', 'shipper_id');
    }

    public function exhibitor(): object
    {
        return $this->belongsTo('Entity', 'exhibitor_id');
    }

    public function zone(): object
    {
        return $this->belongsTo('ShowPlace', 'zone');
    }

    public function color(): object
    {
        return $this->belongsTo('ShowPlace', 'zone');
    }

    public function notes()
    {
        return $this->hasMany('Note', 'transaction_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function shipments(): object
    {
        return $this->hasMany('Shipment', 'transaction_id');
    }
}
