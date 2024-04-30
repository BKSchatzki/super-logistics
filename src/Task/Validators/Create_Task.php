<?php

namespace SL\Task\Validators;

use SL\Core\Validator\Abstract_Validator;

class Create_Task extends Abstract_Validator {
    public function messages() {
        return [
            'title.required'      => __( 'Task title is required.', 'super-logistics' ),
            'project_id.required' => __( 'Project ID is required.', 'super-logistics' ),
            'project_id.numeric'  => __( 'Project ID shoud be numeric.', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title'      => 'required',
            'project_id' => 'required|numeric',
        ];
    }
}
