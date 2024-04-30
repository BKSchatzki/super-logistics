<?php
namespace SL\Global_Kanboard\Models;

use SL\Common\Models\Boardable;
use SL\Task\Models\Task;

class Global_Kanboard_Boardable extends Boardable {

	public function tasks($project_id = false) {
		if ( $project_id ) {
			return $this->hasOne( 'SL\Task\Models\Task', 'id', 'boardable_id')
				->where( 'project_id', $project_id );
		}

		return $this->hasOne( 'SL\Task\Models\Task', 'id', 'boardable_id');
	}
}
