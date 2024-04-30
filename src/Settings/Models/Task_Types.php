<?php

namespace SL\Settings\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;

class Task_Types extends Eloquent {

    use Model_Events;

    protected $table = 'pm_task_types';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'created_by',
        'updated_by',
    ];
}
