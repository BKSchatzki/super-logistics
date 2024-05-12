<?php

use SL\Core\Router\Router;
use SL\Core\Permissions\Authentic;

$router = Router::singleton();

$permission = ['SL\Core\Permissions\Authentic'];

$router->get( 'users', 'SL/User/Controllers/User_Controller@index' )
    ->permission($permission);

$router->post( 'users', 'SL/User/Controllers/User_Controller@store' )
    ->permission($permission);

$router->get( 'users/{id}', 'SL/User/Controllers/User_Controller@show' )
    ->permission($permission);

$router->get( 'users/search', 'SL/User/Controllers/User_Controller@search' )
    ->permission($permission);

$router->put( 'users/{user_id}/roles', 'SL/User/Controllers/User_Controller@update_role' )
    ->permission($permission);

$router->post( 'save_users_map_name', 'SL/User/Controllers/User_Controller@save_users_map_name' )
    ->permission($permission);

$router->get( 'user-all-projects', 'SL/User/Controllers/User_Controller@get_user_all_projects' )
    ->permission($permission);

$router->get( 'current-user', 'SL/User/Controllers/User_Controller@showCurrent' )
    ->permission($permission);
