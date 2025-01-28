<?php

namespace BigTB\SL\API\Entity\Models;

use Illuminate\Database\Eloquent\Model;

class ShowPlace extends Model {

	protected $table = 'sl_show_places';

	protected $fillable = [
		'type',
		'show_id',
		'name',
		'trashed'
	];

	public $timestamps = false;

	public function show(): object {
		return $this->belongsTo( Show::class, 'show_id', 'entity_id' );
	}
}