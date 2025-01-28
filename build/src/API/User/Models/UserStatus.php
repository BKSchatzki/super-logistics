<?php

namespace BigTB\SL\API\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model {

	protected $primaryKey = 'user_id';

	protected $table = 'sl_user_status';

	protected $fillable = [
		'user_id',
		'active',
		'trashed'
	];

	public $timestamps = false;

	public function user(): object {
		return $this->belongsTo( User::class, 'user_id', 'ID' );
	}
}