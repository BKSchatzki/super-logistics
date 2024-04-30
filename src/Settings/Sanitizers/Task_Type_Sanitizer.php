<?php

namespace SL\Settings\Sanitizers;

use SL\Core\Sanitizer\Abstract_Sanitizer;

class Task_Type_Sanitizer extends Abstract_Sanitizer {
	public function filters() {
        return [
            'title'       => 'trimer|pm_kses',
            'description' => 'trimer|pm_kses',
        ];
    }
}
