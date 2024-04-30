<?php

namespace SL\Category\Validators;

use SL\Core\Validator\Abstract_Validator;

class Update_Category extends Abstract_Validator {
    public function messages() {
        return [
            'title.required' => __( 'Category title is required.', 'super-logistics' ),
            'id.required'    => __( 'Category ID is required.', 'super-logistics' ),
            'id.gtz'         => __( 'Category ID must be greater than zero', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title' => 'required',
            'id'    => 'required|gtz', //Greater than zero (gtz)
        ];
    }
}
