<?php

namespace SL\Discussion_Board\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;
use SL\Comment\Models\Comment;
use SL\File\Models\File;
use SL\Common\Models\Boardable;
use SL\User\Models\User;
use SL\Milestone\Models\Milestone;
use SL\Common\Models\Meta;

class Discussion_Board extends Eloquent {
    use Model_Events;

    protected $table = 'pm_boards';

    protected $fillable = [
        'title',
        'description',
        'order',
        'is_private',
        'project_id',
        'created_by',
        'updated_by',
    ];

    protected $attributes = ['type' => 'discussion_board'];

    public function newQuery( $except_deleted = true ) {
        return parent::newQuery( $except_deleted )->where( 'type', '=', 'discussion_board' );
    }

    public function comments() {
        return $this->hasMany( 'SL\Comment\Models\Comment', 'commentable_id' )->where( 'commentable_type', 'discussion_board' );
    }

    public function files() {
        return $this->hasMany( 'SL\File\Models\File', 'fileable_id' )->where( 'fileable_type', 'discussion_board' );
    }

    public function users() {
        return $this->belongsToMany( 'SL\User\Models\User', pm_tb_prefix() . 'pm_boardables', 'board_id', 'boardable_id')
            ->where( 'board_type', 'discussion_board' )
            ->where( 'boardable_type', 'user' );
    }

    public function milestones() {
        return $this->belongsToMany( 'SL\Milestone\Models\Milestone', pm_tb_prefix() . 'pm_boardables', 'boardable_id', 'board_id' )
            ->where( 'board_type', 'milestone' )
            ->where( 'boardable_type', 'discussion_board' );
    }

    public function boardables() {
        return $this->hasMany( 'SL\Common\Models\Boardable', 'boardable_id' )->where( 'boardable_type', 'discussion_board' );
    }

    public function metas() {
        return $this->hasMany( 'SL\Common\Models\Meta', 'entity_id' )
            ->where( 'entity_type', 'discussion_board' );
    }

}
