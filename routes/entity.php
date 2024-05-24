<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get('entities', 'SL/Entity/Controllers/EntityController@show');

$router->post('entities', 'SL/Entity/Controllers/EntityController@store');

