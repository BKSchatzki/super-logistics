<?php

namespace SL\Global_Kanboard\Transformers;

use League\Fractal\TransformerAbstract;
use SL\User\Transformers\User_Transformer;
use SL\Common\Traits\Resource_Editors;
use Carbon\Carbon;
use SL\Task\Models\Task;
use SL\Global_Kanboard\Models\Global_Kanboard;
use SL\Task\Transformers\Task_Transformer;

class Global_Kanboard_Transformer extends TransformerAbstract {

    use Resource_Editors;

    protected $defaultIncludes = [
        //'task'
    ];

    protected $availableIncludes = [
        'tasks'
    ];

    public function transform( Global_Kanboard $item ) {
        return [
            'id'         => $item->id,
            'title'      => $item->title,
            'order'      => $item->order,
            'project_id' => $item->project_id,
            'automation' => $this->filter_automation( $item ),
            'header_background' => $this->get_header_background( $item )
        ];
    }

    public function filter_automation( $item ) {
        $metas = $item->meta->toArray();

        foreach ( $metas as $key => $meta ) {
            if ( $meta['entity_type'] == 'kanboard' && $meta['meta_key'] == 'automation' ) {
                return $meta['meta_value'];
            }
        }

        return [];
    }

    public function get_header_background( $item ) {
        $metas = $item->meta->toArray();

        foreach ( $metas as $key => $meta ) {
            if ( $meta['entity_type'] == 'kanboard' && $meta['meta_key'] == 'header_background' ) {
                return $meta['meta_value'];
            }
        }

        return '#fbfcfd';
    }
}
