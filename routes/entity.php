<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get('clients', 'SL/Entity/Controllers/EntityController@showAllClients');

$router->get('carriers', 'SL/Entity/Controllers/EntityController@showAllCarriers');

$router->get('exhibitors', 'SL/Entity/Controllers/EntityController@showAllExhibitors');

$router->get('shippers', 'SL/Entity/Controllers/EntityController@showAllShippers');

$router->post('entity', 'SL/Entity/Controllers/EntityController@store');

