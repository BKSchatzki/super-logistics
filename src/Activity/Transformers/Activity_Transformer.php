<?php

namespace SL\Activity\Transformers;

use League\Fractal\TransformerAbstract;
use SL\Activity\Models\Carrier;
use SL\User\Transformers\User_Transformer;
use SL\Project\Transformers\Project_Transformer;

class Activity_Transformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'actor', 'project'
    ];

    public function transform(Carrier $item ) {
        if ( $item->action == 'cpm_migration' ){
            $message = $item->meta['text'];
        }else {
            $message = pm_get_text( "activities.{$item->action}" ); 
        }

        return [
            'id'            => (int) $item->id,
            'message'       => $message,
            'action'        => $item->action,
            'action_type'   => $item->action_type,
            'meta'          => $this->parse_meta( $item ),
            'committed_at'  => format_date( $item->created_at ),
            'resource_id'   => $item->resource_id,
            'resource_type' => $item->resource_type,
        ];
    }

    public function includeActor(Carrier $item ) {
        $actor = $item->actor;

        return $this->item( $actor, new User_Transformer );
    }

    public function includeProject(Carrier $item ) {
        $project = $item->project;
        $project_transformer = new Project_Transformer;
        $project_transformer = $project_transformer->setDefaultIncludes([]);
        return $this->item ( $project, $project_transformer);
    }

    private function parse_meta(Carrier $activity ) {
        $parsed_meta = [];

        switch ( $activity->resource_type ) {
            case 'task':
                $parsed_meta = $this->parse_meta_for_task( $activity );
                break;

            case 'task_list':
                $parsed_meta = $this->parse_meta_for_task_list( $activity );
                break;

            case 'discussion_board':
                $parsed_meta = $this->parse_meta_for_discussion_board( $activity );
                break;

            case 'milestone':
                $parsed_meta = $this->parse_meta_for_milestone( $activity );
                break;

            case 'project':
                $parsed_meta = $this->parse_meta_for_project( $activity );
                break;

            case 'comment':
                $parsed_meta = $this->parse_meta_for_comment( $activity );
                break;

            case 'file':
                $parsed_meta = $this->parse_meta_for_file( $activity );
                break;
        }

        return $parsed_meta;
    }

    private function parse_meta_for_task(Carrier $activity ) {
        return $activity->meta;
    }

    private function parse_meta_for_task_list(Carrier $activity ) {
        return $activity->meta;
    }

    private function parse_meta_for_discussion_board(Carrier $activity ) {
        return $activity->meta;
    }

    private function parse_meta_for_milestone(Carrier $activity ) {
        return $activity->meta;
    }

    private function parse_meta_for_project(Carrier $activity ) {
        return $activity->meta;
    }

    private function parse_meta_for_file(Carrier $activity ) {
        return $activity->meta;
    }

    private function parse_meta_for_comment(Carrier $activity ) {
        $meta = [];

        if ( ! is_array( $activity ) ) {
            return $meta;
        }

        foreach ($activity->meta as $key => $value) {
            if ( $key == 'commentable_type' && $value == 'file' ) {
                $trans_commentable_type = pm_get_text( "resource_types.{$value}" );
                $meta['commentable_id'] = $activity->meta['commentable_id'];
                $meta['commentable_type'] = $activity->meta['commentable_type'];
                $meta['trans_commentable_type'] = $trans_commentable_type;
                $meta['commentable_title'] = $trans_commentable_type;
            } elseif ( $key == 'commentable_type' ) {
                $trans_commentable_type = pm_get_text( "resource_types.{$value}" );
                $meta['commentable_id'] = $activity->meta['commentable_id'];
                $meta['commentable_type'] = $activity->meta['commentable_type'];
                $meta['trans_commentable_type'] = $trans_commentable_type;
                $meta['commentable_title'] = $activity->meta['commentable_title'];
            }
        }

        return $meta;
    }
}
