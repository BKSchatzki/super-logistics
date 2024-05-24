<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'items', 'SL/Transaction/Controllers/TransactionController@index' );

$router->post( 'items', 'SL/Transaction/Controllers/TransactionController@store' );

