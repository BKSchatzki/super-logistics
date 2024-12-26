<?php

namespace BigTB\SL\API\Transaction\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'sl_transactions';
	protected $fillable = [
		'id',
		'name',
		'show_id',
		'client_id',
		'zone',
		'active',
		'created_at',
		'updated_at'
	];
    public $timestamps = true;

    public function show(): object
    {
        return $this->belongsTo('BigTB\SL\API\Show\Models\Show', 'show_id');
    }

    public function client(): object
    {
        return $this->belongsTo('BigTB\SL\API\Entity\Models\Entity', 'client_id');
    }

    public function items(): object
    {
        return $this->hasMany('BigTB\SL\API\Package\Models\Item', 'transaction_id');
    }

    public function updates(): object
    {
        return $this->hasMany('BigTB\SL\API\Update\Models\Update', 'transaction_id');
    }
}
