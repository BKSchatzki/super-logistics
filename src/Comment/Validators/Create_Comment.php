<?php

namespace SL\Comment\Validators;

use SL\Core\Validator\Abstract_Validator;

class Create_Comment extends Abstract_Validator {
    public function messages() {
        return [
            'content.required' => __( 'Comment title is required.', 'super-logistics' ),
            'project_id.required' => __( 'Project ID is required.', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'content'    => 'required',
            'project_id' => 'required'
        ];
    }
}
