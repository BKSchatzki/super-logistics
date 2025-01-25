<?php

namespace BigTB\SL\API\Entity\Models;

use BigTB\SL\API\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class Show extends Model {
	protected $table = 'sl_shows';
	protected $fillable = [
		'entity_id',
		'client_id',
		'min_carat_weight',
		'carat_weight_inc',
		'date_start',
		'date_end',
		'floor_plan_path',
	];

	public $timestamps = false;

	protected static function boot(): void {
		parent::boot();

		static::deleting( function ( $show ) {
			$show->entity()->delete();
		} );
	}

	public function transactions(): object {
		return $this->hasMany( Transaction::class, 'show_id' );
	}

	public function entity(): object {
		return $this->belongsTo( Entity::class, 'entity_id' );
	}

	public function client(): object {
		return $this->belongsTo( Entity::class, 'client_id' );
	}

	// While show_id intuitively points to the id on this model/table,
	// it actually points to the entity_id on the Entity model/table
	// Again, shows are primarily a type of entity, the extra information is stored in the show table

	public function places(): object {
		return $this->hasMany( ShowPlace::class, 'show_id', 'entity_id' );
	}

	public function zones(): object {
		return $this->hasMany( ShowPlace::class, 'show_id', 'entity_id' )->where( 'type', 1 );
	}

	public function booths(): object {
		return $this->hasMany( ShowPlace::class, 'show_id', 'entity_id' )->where( 'type', 2 );
	}
}
