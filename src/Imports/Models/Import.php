<?php

namespace SL\Imports\Models;

use SL\Core\DB_Connection\Model as Eloquent;

class Import extends Eloquent {

    protected $table = 'pm_imports';

    protected $fillable = [
        'type',
        'remote_id',
        'local_id',
        'creator_id',
        'source'
    ];
}
