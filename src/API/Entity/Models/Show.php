<?php

namespace BigTB\SL\API\Entity\Models;

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

		static::deleting(function ($show) {
			$show->entity()->delete();
		});
	}

    public function transactions():object {
        return $this->hasMany('BigTB\SL\API\Transaction\Models\Transaction', 'show_id');
    }

    public function entity():object {
        return $this->belongsTo('BigTB\SL\API\Entity\Models\Entity', 'entity_id');
    }

	public function client():object {
		return $this->belongsTo('BigTB\SL\API\Entity\Models\Entity', 'client_id');
	}
}
