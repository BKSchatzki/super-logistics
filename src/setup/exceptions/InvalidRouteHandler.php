<?php

namespace BigTB\SL\Setup\Exceptions;

use Exception;

class InvalidRouteHandler extends Exception
{
	public function __construct( $message )
    {
        $message = $message . ' is not a valid route handler';

        parent::__construct( $message );
    }
}
