<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'shows', 'SL/Show/Controllers/ShowController@show' );

$router->get( 'shows/relevant', 'SL/Show/Controllers/ShowController@showRelevant' );

$router->get( 'shows/all', 'SL/Show/Controllers/ShowController@showAll' );

$router->post( 'shows', 'SL/Show/Controllers/ShowController@store' );

