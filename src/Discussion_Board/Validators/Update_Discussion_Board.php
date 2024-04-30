<?php

namespace SL\Discussion_Board\Validators;

use SL\Core\Validator\Abstract_Validator;

class Update_Discussion_Board extends Abstract_Validator {
    public function messages() {
        return [
            'title.required' => __( 'Discussion title is required.', 'super-logistics' ),
            'id.required'    => __( 'Discussion ID is required.', 'super-logistics' ),
            'id.gtz'         => __( 'Discussion ID must be greater than zero', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title' => 'required',
            'id'    => 'required|gtz', //Greater than zero (gtz)
        ];
    }
}
