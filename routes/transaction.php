<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'transactions', 'SL/Transaction/Controllers/TransactionController@index' );

