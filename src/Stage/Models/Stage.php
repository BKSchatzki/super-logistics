<?php

namespace WeDevs\PM\Stage\Models;

use WeDevs\PM\Core\DB_Connection\Model as Eloquent;
use WeDevs\PM\Common\Traits\Model_Events;

class Stage extends Eloquent {

    use Model_Events;

    protected $table = 'pm_stages';

    protected $fillable = [
        'title',
        'description',
        'type',
    ];

    public function project() {
        return $this->belongsToMany( 'WeDevs\PM\Project\Models\Project', pm_tb_prefix() . 'pm_stage_projects', 'stage_id', 'project_id' );
    }
}
