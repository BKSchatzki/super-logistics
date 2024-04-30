<?php

namespace SL\Comment\Validators;

use SL\Core\Validator\Abstract_Validator;

class Update_Comment extends Abstract_Validator {
    public function messages() {
        return [
            'content.required' => __( 'Comment title is required.', 'super-logistics' ),
            'id.required'      => __( 'Comment ID is required.', 'super-logistics' ),
            'id.gtz'           => __( 'Comment ID must be greater than zero', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'content' => 'required',
            'id'      => 'required|gtz', //Greater than zero (gtz)
        ];
    }
}
