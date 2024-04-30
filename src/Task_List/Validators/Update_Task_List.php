<?php

namespace SL\Task_List\Validators;

use SL\Core\Validator\Abstract_Validator;

class Update_Task_List extends Abstract_Validator {
    public function messages() {
        return [
            'title.required' => __( 'Task list title is required.', 'super-logistics' ),
            'id.required'    => __( 'Task list ID is required.', 'super-logistics' ),
            'id.gtz'         => __( 'Task list ID must be greater than zero', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title' => 'required',
            'id'    => 'required|gtz', //Greater than zero (gtz)
        ];
    }
}
