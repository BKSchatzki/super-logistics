<?php

namespace SL\Role\Validators;

use SL\Core\Validator\Abstract_Validator;

class Update_Role extends Abstract_Validator {
    public function messages() {
        return [
            'title.required' => __( 'Role title is required.', 'super-logistics' ),
            'id.required'    => __( 'Role ID is required.', 'super-logistics' ),
            'id.gtz'         => __( 'Role ID must be greater than zero', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title' => 'required',
            'description' => 'pm_kses',
            'id'    => 'required|gtz', //Greater than zero (gtz)
        ];
    }
}
