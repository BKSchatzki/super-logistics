<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get('entities', 'SL/Entity/Controllers/EntityController@show');

$router->post('entities', 'SL/Entity/Controllers/EntityController@store');

$router->post('entities/code', 'SL/Entity/Controllers/EntityController@updateCode');

$router->get('entities/codes', 'SL/Entity/Controllers/EntityController@getCodes');

$router->post('entities/register', 'SL/Entity/Controllers/EntityController@registerUser');
