<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'transactions/search', 'SL/Transaction/Controllers/TransactionController@search' );

$router->get( 'transactions', 'SL/Transaction/Controllers/TransactionController@get' );

$router->post( 'transactions', 'SL/Transaction/Controllers/TransactionController@store' );

$router->post( 'transactions/update', 'SL/Transaction/Controllers/TransactionController@update' );

$router->delete( 'transactions/update/note/{update_id}', 'SL/Transaction/Controllers/TransactionController@removeNote' );

$router->delete( 'transactions/{id}', 'SL/Transaction/Controllers/TransactionController@trash' );

$router->get( 'transactions/labels', 'SL/Transaction/Controllers/TransactionController@createLabels' );

$router->post( 'transactions/notes', 'SL/Transaction/Controllers/TransactionController@storeNote' );

