<?php

namespace SL\Discussion_Board\Validators;

use SL\Core\Validator\Abstract_Validator;

class Create_Discussion_Board extends Abstract_Validator {
    public function messages() {
        return [
            'title.required' => __( 'Discussion title is required.', 'super-logistics' ),
            'project_id.required' => __( 'Project ID is required.', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title'      => 'required',
            'project_id' => 'required'
        ];
    }
}
