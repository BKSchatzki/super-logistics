<?php 

namespace SL\Core\Notifications\Emails;

/**
* Email Notification When a new project created
*/
use SL\Core\Notifications\Email;
use SL\Project\Models\Project;
use SL\Task_List\Models\Task_List;
use SL\Task\Models\Task;
use SL\Discussion_Board\Models\Discussion_Board;
use SL\Common\Models\Meta;
use SL\Comment\Models\Comment;
use SL\File\Models\File;

class New_Comment_Notification extends Email {
    
    function __construct() {

        add_action('pm_after_new_comment_notification', array($this, 'trigger'), 10, 2 );
    }

    public function trigger( $commentData, $request ) {

        if ( empty( $request['notify_users'] ) ){
            return ;
        }
        
        $project      = Project::with('assignees', 'managers')->find( $request['project_id'] );
        $users        = array();
        $notify_users = explode( ',',  $request['notify_users'] );

        foreach ( $notify_users as $u ) {
            if( $this->is_enable_user_notification( $u ) ){
                if( $this->is_enable_user_notification_for_notification_type( $u, '_cpm_email_notification_new_comment' ) ){
                    $users[] = $project->assignees->where( 'ID', $u )->first()->user_email;
                }
            }
        }

        if( $this->notify_manager() ){
            foreach ( $project->managers->toArray() as $u ) {
                if ( !in_array( $u['user_email'], $users ) ) {
                    $users[] = $u['user_email'];
                }
            }
        }

        if ( !$users ){
            return ; 
        }

        if ( $request['commentable_type'] == 'discussion_board' ) {
            $type = __( 'Message', 'super-logistics' );
            $comment_link = $this->pm_link() . '#/projects/'.$project->id.'/discussions/'.$request['commentable_id'];
            $title = Discussion_Board::find( $request['commentable_id'] )->title;

        } else if ( $request['commentable_type'] == 'task_list' ) {
            $type = __( 'Task List', 'super-logistics' );
            $comment_link = $this->pm_link() . '#/projects/'.$project->id.'/task-lists/'.$request['commentable_id'];
            $title = Task_List::find( $request['commentable_id'] )->title;

        } else if ( $request['commentable_type'] == 'task' ) {
            $type        = __( 'Task', 'super-logistics' );
            $comment_link = $this->pm_link() . '#/projects/'.$project->id. '/task-lists/tasks/'.$request['commentable_id'];
            $title = Task::find( $request['commentable_id'] )->title;

        } else if ( $request['commentable_type'] == 'file' ) {
            $type        = __( 'File', 'super-logistics' );
            $file = File::find($request['commentable_id']);
            $comment_link = $this->pm_link() . '#/projects/'. $project->id .'/files/'. $file->parent .'/'. $file->type .'/'. $request['commentable_id'];
            $filemeta = Meta::where( 'project_id', $request['project_id'] )
                            ->where( 'entity_type', 'file' )
                            ->where( 'entity_id',  $request['commentable_id'])
                            ->where( 'meta_key', 'title' )
                            ->first();
            $title = $filemeta->meta_value;
        }

        $template_name = apply_filters( 'pm_new_comment_email_template_path', $this->get_template_path( '/html/new-comment.php' ) );
        $subject       = sprintf( __( '[%s][%s] New Comment on: %s', 'super-logistics' ), $this->get_blogname(), $project->title , $title );       
        
        $message = $this->get_content_html( $template_name, [
            'id'                => $commentData['data']['id'],
            'content'           => $request['content'],
            'updater'           => $commentData['data']['updater']['data']['display_name'],
            'commnetable_title' => $title,
            'commnetable_type'  => $type,
            'comment_link'      => $comment_link,
            'created_at'        => $commentData['data']['created_at']['date'],
            'creator'           => $commentData['data']['creator'],
        ] );

        $this->send( $users, $subject, $message );
    }

}
