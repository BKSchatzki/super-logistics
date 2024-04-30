<?php

namespace SL\My_Task\Transformers;

use SL\Project\Transformers\Project_Transformer as PTransformer;
use SL\Task\Transformers\Task_Transformer;
use League\Fractal\TransformerAbstract;
use SL\Project\Models\Project;
use SL\Task\Models\Task;
use SL\User\Models\User;

class Project_Transformer extends PTransformer
{

     protected $defaultIncludes = [
        'tasks'
    ];

    public function includeTasks( Project $item ) {
        $tasks = $item->tasks;
        $transfomer = new Task_Transformer();
       	$transfomer = $transfomer->setDefaultIncludes(['assignees']);

        return $this->collection( $tasks, $transfomer);
    }
}
