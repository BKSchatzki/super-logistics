<?php
namespace SL\Global_Kanboard\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;
use SL\Common\Models\Boardable;
use SL\Task\Models\Task;
use SL\Common\Models\Meta;
use Carbon\Carbon;


class Global_Kanboard extends Eloquent {

    use Model_Events;

    protected $table = 'pm_boards';

    protected $fillable = [
        'title',
        'description',
        'order',
        'project_id',
        'created_by',
        'updated_by'
    ];

    protected $attributes = ['type' => 'kanboard'];

    public function tasks() {

    	return $this->belongsToMany( 'SL\Task\Models\Task', pm_tb_prefix() . 'pm_boardables',
    	    'board_id', 'boardable_id' )
            ->where( 'board_type', 'kanboard' )
            ->where( 'boardable_type', 'task' )
            ->orderBy( 'order', 'ASC' );
    }

    public function projects() {

    	return $this->belongsToMany( 'SL\Project\Models\Project', pm_tb_prefix() . 'pm_boardables',
    	    'board_id', 'boardable_id' )
            ->where( 'board_type', 'kanboard' )
            ->where( 'boardable_type', 'project' )
            ->orderBy( 'order', 'ASC' );
    }

    public function boardables() {
        return $this->hasMany( 'SL\Common\Models\Boardable', 'board_id' )->where( 'board_type', 'kanboard' );
    }

    public function meta() {
        return $this->hasMany( 'SL\Common\Models\Meta', 'entity_id' )->where( 'entity_type', 'kanboard' );
    }

    public static function latest_order( $project_id, $board_type ) {
        $board = self::where( 'project_id', $project_id )
            ->where( 'type', $board_type )
            ->orderBy( 'order', 'DESC' )
            ->first();

        return $board ? $board->order : 0;
    }
}
