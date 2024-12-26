<?php

namespace BigTB\SL\API\Package\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model {
    protected $table = 'sl_items';
	protected $fillable = [
		'transaction_id',
		'type',
		'pcs',
		'bol_count',
		'weight',
		'special',
		'notes',
		'tracking',
		'carrier',
		'exhibitor',
		'returning',
		'active'
	];

    public $timestamps = false;

    public function transaction(): object {
        return $this->belongsTo( 'BigTB\SL\API\Transaction\Models\Transaction' );
    }

	public function carrier(): object {
		return $this->belongsTo( 'BigTB\SL\API\Entity\Models\Entity' );
	}
}
