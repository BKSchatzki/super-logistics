<?php

namespace SL\Core\Database;

use SL\Core\Database\Migration;

abstract class Abstract_Migration implements Migration {
    public function run() {
        $this->schema();
    }

    abstract public function schema();
}
