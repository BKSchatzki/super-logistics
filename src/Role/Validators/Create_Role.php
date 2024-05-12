<?php

namespace SL\Role\Validators;

use SL\Core\Validator\Abstract_Validator;

class Create_Role extends Abstract_Validator {
    public function messages() {
        return [
            'title.required' => __( 'Role title is required.', 'super-logistics' ),
        ];
    }

    public function rules() {
        return [
            'title'  => 'required',
            'description' => 'pm_kses',
        ];
    }
}
