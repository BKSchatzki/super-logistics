<?php

use SL\Core\Router\Router;
use SL\Core\Permissions\Authentic;

$router = Router::singleton();

$permission = ['SL\Core\Permissions\Authentic'];

$router->get( 'users', 'SL/User/Controllers/UserController@index' )
    ->permission($permission);

$router->post( 'users', 'SL/User/Controllers/UserController@store' )
    ->permission($permission);

$router->get( 'app-users', 'SL/User/Controllers/UserController@showAppUsers' )
    ->permission($permission);

$router->get( 'users/{id}', 'SL/User/Controllers/UserController@show' )
    ->permission($permission);

$router->get( 'users/search', 'SL/User/Controllers/UserController@search' )
    ->permission($permission);

$router->put( 'users/{user_id}/roles', 'SL/User/Controllers/UserController@update_role' )
    ->permission($permission);

$router->post( 'save_users_map_name', 'SL/User/Controllers/UserController@save_users_map_name' )
    ->permission($permission);

$router->get( 'user-all-projects', 'SL/User/Controllers/UserController@get_user_all_projects' )
    ->permission($permission);

$router->get( 'current-user', 'SL/User/Controllers/UserController@showCurrent' )
    ->permission($permission);

$router->get( 'client', 'SL/User/Controllers/UserController@client' )
    ->permission($permission);
