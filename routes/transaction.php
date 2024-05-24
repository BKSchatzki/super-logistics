<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'transactions', 'SL/Transaction/Controllers/TransactionController@show' );

$router->post( 'transactions', 'SL/Transaction/Controllers/TransactionController@store' );

