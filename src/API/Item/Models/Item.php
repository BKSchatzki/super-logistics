<?php

namespace BigTB\SL\API\Item\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    protected $table = 'sl_items';
    protected $fillable = [
        'transaction_id',
        'type',
        'pcs',
        'bol_count',
        'weight',
        'notes',
        'tracking',
        ];
    public $timestamps = false;

    public function transaction(): object {
        return $this->belongsTo( 'BigTB\SL\API\Transaction\Models\Transaction' );
    }
}
