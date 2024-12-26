<?php

namespace BigTB\SL\Setup\Exceptions;

use Exception;

class UndefinedMethodCall extends Exception
{
    public function __construct( $class_name, $method_name )
    {
        $message = 'Method, ' . $method_name . ', is not defined ' . 'in ' . $class_name;

        parent::__construct( $message );
    }
}
