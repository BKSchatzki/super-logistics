<?php

namespace SL\Core\Database;

interface Migration {
    public function schema();
    public function run();
}
