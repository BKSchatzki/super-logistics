<?php

namespace SL\Milestone\Validators;

use SL\Core\Validator\Abstract_Validator;

class Update_Milestone extends Abstract_Validator {
    public function messages() {
        return [
            'title.required' => __( 'Milestone title is required.', 'super-logistics' ),
            'id.required'    => __( 'Milestone ID is required.', 'super-logistics' ),
            'id.gtz'         => __( 'Milestone ID must be greater than zero', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title' => 'required',
            'id'    => 'required|gtz', //Greater than zero (gtz)
        ];
    }
}
