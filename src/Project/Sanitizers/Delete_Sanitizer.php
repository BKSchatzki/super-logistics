<?php

namespace SL\Project\Sanitizers;

use SL\Core\Sanitizer\Abstract_Sanitizer;

class Delete_Sanitizer extends Abstract_Sanitizer {
	public function filters() {
        return [
			'id' => 'absolute',
        ];
    }
}
