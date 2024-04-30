<?php

namespace SL\Task\Validators;

use SL\Core\Validator\Abstract_Validator;

class Update_Task extends Abstract_Validator {
    public function messages() {
        return [
            'title.required' => __( 'Task title is required.', 'super-logistics' ),
            'id.required'    => __( 'Task ID is required.', 'super-logistics' ),
            'id.gtz'         => __( 'Task ID must be greater than zero', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title' => 'required',
            'id'    => 'required|gtz', //Greater than zero (gtz)
        ];
    }
}
