<?php

namespace SL\Comment\Validators;

use SL\Core\Sanitizer\Abstract_Sanitizer;

class Comment_Sanitizer extends Abstract_Sanitizer {
	public function filters() {
        return [
            'content' => 'pm_kses',
        ];
    }
}
