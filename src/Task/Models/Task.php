<?php

namespace SL\Task\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;
use SL\Task\Task_Model_Trait;
use SL\Task_List\Models\Task_List;
use SL\Common\Models\Board;
use SL\Common\Models\Boardable;
use SL\File\Models\File;
use SL\Comment\Models\Comment;
use SL\Common\Models\Assignee;
use SL\Project\Models\Project;
use SL\Common\Models\Meta;
use SL\Activity\Models\Carrier;
use Carbon\Carbon;


class Task extends Eloquent {
    use Model_Events, Task_Model_Trait;

    protected $table = 'pm_tasks';

    const INCOMPLETE = 0;
    const COMPLETE   = 1;
    const PENDING    = 2;

    protected $fillable = [
        'title',
        'description',
        'estimation',
        'start_at',
        'due_date',
        'complexity',
        'priority',
        'payable',
        'recurrent',
        'status',
        'is_private',
        'project_id',
        'completed_by',
        'completed_at',
        'parent_id',
        'created_by',
        'updated_by'
    ];

    protected $dates = ['start_at', 'due_date', 'completed_at'];

    protected $attributes = [
        'priority' => 1,
    ];

    public function scopeCompleted($query) {
        return $query->where('status', Task::COMPLETE);
    }

    public function scopeIncomplete($query) {
        return $query->where('status', Task::INCOMPLETE);
    }

    public function scopeOverdue( $query ) {
        $today = Carbon::now();
        return $query->whereDate('due_date', '<', $today);
    }

    public function scopeParent( $query ){
        return $query->where('parent_id', 0);
    }

    public function task_lists() {
        return $this->belongsToMany( 'SL\Task_List\Models\Task_List', pm_tb_prefix() . 'pm_boardables', 'boardable_id', 'board_id' )
            ->where( pm_tb_prefix() . 'pm_boardables.board_type', 'task_list')
            ->where( pm_tb_prefix() . 'pm_boardables.boardable_type', 'task');
    }

    public function boards() {
        return $this->belongsToMany( 'SL\Common\Models\Board', pm_tb_prefix() . 'pm_boardables', 'boardable_id', 'board_id' )
            ->where( pm_tb_prefix() . 'pm_boardables.boardable_type', 'task');
    }

    public function boardables() {
        return $this->hasMany( 'SL\Common\Models\Boardable', 'boardable_id' )->where( 'boardable_type', 'task' );
    }

    public function files() {
        return $this->hasMany( 'SL\File\Models\File', 'fileable_id' )->where( 'fileable_type', 'task' );
    }

    public function comments() {
        return $this->hasMany( 'SL\Comment\Models\Comment', 'commentable_id' )->whereIn( 'commentable_type', ['task'] );
    }

    public function assignees() {
        return $this->hasMany( 'SL\Common\Models\Assignee', 'task_id' );
    }
    
    public function user() {
        return $this->belongsToMany( 'SL\User\Models\User', pm_tb_prefix() . 'pm_assignees', 'task_id', 'assigned_to' )
            ->withPivot('completed_at', 'assigned_at', 'started_at', 'status');
    }

    public function activities() {
        return $this->hasMany( 'SL\Activity\Models\Carrier', 'resource_id' )->where( 'resource_type', 'task' )->orderBy( 'created_at', 'DESC' );
    }

    public function projects() {
        return $this->belongsTo( 'SL\Project\Models\Project', 'project_id');
    }

    public function task_model( $key = '' ) {
        return apply_filters( 'task_model', $this, $key );
    }

    public function metas() {
        return $this->hasMany( 'SL\Common\Models\Meta', 'entity_id' )
            ->where( 'entity_type', 'task' );
    }

    public function completer() {
        return $this->belongsTo( 'SL\User\Models\User', 'completed_by' );
    }

    public function labels() {
        return apply_filters( 'pm_task_model_labels', $this );
    }

}
