<?php

namespace SL\Role\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;

class Role extends Eloquent {

    use Model_Events;

    protected $table = 'sl_roles';

    protected $fillable = [
        'title',
        'description',
        'slug',
        'status',
        'created_by',
        'updated_by',
    ];

    
}
