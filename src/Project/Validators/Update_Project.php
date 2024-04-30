<?php

namespace SL\Project\Validators;

use SL\Core\Validator\Abstract_Validator;

class Update_Project extends Abstract_Validator {
    public function messages() {
        return [
            'title.required'  => __( 'Project title is required.', 'super-logistics' ),
            'title.pm_unique' => __( 'Project title must be unique.', 'super-logistics' ),
            'id.required'     => __( 'Project ID is required.', 'super-logistics' ),
            'id.gtz'          => __( 'Project ID must be greater than zero', 'super-logistics' ),
        ];
    }

    public function rules() {
        $id = $this->request->get_param( 'id' );
        
        if(is_array( $id )) {
            return [];
        }
        
        return [
            'title' => 'required|pm_unique:Project,title,'.$id,
            'id'    => 'required|gtz', //Greater than zero (gtz)
        ];
    }
}
