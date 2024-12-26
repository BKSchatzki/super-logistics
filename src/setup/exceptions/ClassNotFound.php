<?php

namespace BigTB\SL\Setup\Exceptions;

use Exception;

class ClassNotFound extends Exception
{
    public function __construct( $message )
    {
        $message = $message . ' is not found';

        parent::__construct( $message );
    }
}
