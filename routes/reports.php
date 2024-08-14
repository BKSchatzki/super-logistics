<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'reports/trailer-manifest', 'SL/Report/Controllers/TransactionController@index' );

