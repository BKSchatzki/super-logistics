<?php

namespace SL\Comment\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;
use SL\File\Models\File;
use SL\Discussion_Board\Models\Discussion_Board;
use SL\Task_List\Models\Task_List;
use SL\Task\Models\Task;

class Comment extends Eloquent {

    use Model_Events;

    protected $table = 'pm_comments';

    protected $fillable = [
        'content',
        'mentioned_users',
        'commentable_id',
        'commentable_type',
        'project_id',
        'created_by',
        'updated_by',
    ];

    public function replies() {
        return $this->hasMany( $this, 'commentable_id' )->where( 'commentable_type', 'comment' );
    }

    public function files() {
        return $this->hasMany( 'SL\File\Models\File', 'fileable_id' )->where( 'fileable_type', 'comment' );
    }

    public static function parent_comment( $comment_id ) {
        $comment = self::find( $comment_id );

        if ( $comment && $comment->commentable_type == 'comment' ) {
            $comment = self::parent_comment( $comment->commentable_id );
        }

        return $comment;
    }

    public function discussion_board() {
        return $this->belongsTo('SL\Discussion_Board\Models\Discussion_Board', 'commentable_id');
    }

    public function task_list() {
        return $this->belongsTo( 'SL\Task_List\Models\Task_List', 'commentable_id');
    }

    public function task() {
        return $this->belongsTo( 'SL\Task\Models\Task', 'commentable_id');
    }

    public function setMentionedUsersAttribute( $value ) {
        $this->attributes['mentioned_users'] = serialize( $value );
    }

    public function getMentionedUsersAttribute( $value ) {
        return unserialize( $value );
    }
}
