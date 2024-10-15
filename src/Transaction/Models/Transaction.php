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
        'tracking',
        'place',
        'booth',
        'billable_weight',
        'pallet_no',
        'trailer',
        'receiver',
        'freight_type',
        'status'
    ];
    public $timestamps = true;

    public function show(): object
    {
        return $this->belongsTo('SL\Show\Models\Show', 'show_id');
    }

    private static string $entityModel = 'SL\Entity\Models\Entity';

    public function client(): object
    {
        return $this->belongsTo(self::$entityModel, 'client_id');
    }

    public function carrier(): object
    {
        return $this->belongsTo(self::$entityModel, 'carrier_id');
    }

    public function shipper(): object
    {
        return $this->belongsTo(self::$entityModel, 'shipper_id');
    }

    public function exhibitor(): object
    {
        return $this->belongsTo(self::$entityModel, 'exhibitor_id');
    }

    public function showPlace(): object
    {
        return $this->belongsTo('SL\Show\Models\ShowPlace', 'place');
    }

    public function items(): object
    {
        return $this->hasMany('SL\Item\Models\Item', 'transaction_id');
    }

    public function updates(): object
    {
        return $this->hasMany('SL\Update\Models\Update', 'transaction_id');
    }
}
