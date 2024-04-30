<?php

namespace SL\File\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;
use SL\User\Models\User;
use SL\Comment\Models\Comment;
use SL\Common\Models\Board;

class File extends Eloquent {
    use Model_Events;

    protected $table = 'pm_files';

    protected $fillable = [
        'fileable_id',
        'fileable_type',
        'parent',
        'type',
        'attachment_id',
        'parent',
        'project_id',
        'created_by',
        'updated_by'
    ];

    public function comment() {
        return $this->hasOne( 'SL\Comment\Models\Comment', 'id', 'fileable_id');
        return $this->belongsToMany( 'SL\Common\Models\Board', pm_tb_prefix() . 'pm_comments', 'id', 'commentable_id', 'fileable_id');
    }

    public function meta() {
        return $this->hasMany( 'SL\Common\Models\Meta', 'entity_id' )->where('entity_type', 'file');
    }

    public function children() {
        return $this->hasMany( $this, 'parent' );
    }





}
