<?php

namespace SL\Common\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;
use SL\User\Models\User;

class Assignee extends Eloquent {

    use Model_Events;

    protected $table = 'pm_assignees';

    protected $fillable = [
        'task_id',
        'assigned_to',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'created_by',
        'updated_by',
        'project_id'
    ];

    protected $dates = [
        'assigned_at', 'started_at', 'completed_at'
    ];

    public function assigned_user() {
        return $this->belongsTo( 'SL\User\Models\User', 'assigned_to' );
    }
}
