<?php

namespace SL\Milestone\Validators;

use SL\Core\Validator\Abstract_Validator;

class Create_Milestone extends Abstract_Validator {
    public function messages() {
        return [
            'title.required'      => __( 'Milestone title is required.', 'super-logistics' ),
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
