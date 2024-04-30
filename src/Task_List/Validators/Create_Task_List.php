<?php

namespace SL\Task_List\Validators;

use SL\Core\Validator\Abstract_Validator;

class Create_Task_List extends Abstract_Validator {
    public function messages() {
        return [
            'title.required' => __( 'Task list title is required.', 'super-logistics' ),
            'project_id.required' => __( 'Project ID is required.', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title'      => 'required',
            'project_id' => 'required',
        ];
    }
}
