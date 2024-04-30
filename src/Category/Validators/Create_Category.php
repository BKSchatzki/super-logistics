<?php

namespace SL\Category\Validators;

use SL\Core\Validator\Abstract_Validator;

class Create_Category extends Abstract_Validator {
    public function messages() {
        return [
            'title.required' => __( 'Category title is required.', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title'  => 'required',
        ];
    }
}
