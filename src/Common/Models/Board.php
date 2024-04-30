<?php

namespace SL\Common\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;
use SL\Common\Models\Meta;
use SL\Common\Traits\Board_Status;

class Board extends Eloquent {

    use Model_Events, Board_Status;

    protected $table = 'pm_boards';

    protected $fillable = [
        'title',
        'description',
        'order',
        'type',
        'status',
        'project_id',
        'created_by',
        'updated_by',
    ];

    public function metas() {
        return $this->hasMany( 'SL\Common\Models\Meta', 'entity_id' )
            ->whereIn( 'entity_type', [ 'milestone', 'task_list', 'discussion_board' ] );
    }
}
