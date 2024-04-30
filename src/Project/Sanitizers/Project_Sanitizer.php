<?php

namespace SL\Project\Sanitizers;

use SL\Core\Sanitizer\Abstract_Sanitizer;

class Project_Sanitizer extends Abstract_Sanitizer {
	public function filters() {
        return [
            'projectable_type' => 'trimer',
            'title'            => 'trimer|sanitize_text_field',
            'description'      => 'trimer|sanitize_text_field',
            'status'           => 'trimer',
        ];
    }
}
