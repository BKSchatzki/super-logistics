<?php

namespace SL\Settings\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;

class Task_Type_Task extends Eloquent {

    use Model_Events;

    protected $table = 'pm_task_type_task';
    public $timestamps = false;

    protected $primaryKey = 'task_id';

    protected $fillable = [
        'type_id',
        'task_id',
        'list_id',
        'project_id'
    ];
}
