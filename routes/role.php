<?php

use SL\Core\Router\Router;
use SL\Core\Permissions\Administrator;
use SL\Core\Permissions\Authentic;
use SL\Core\Permissions\Project_Manage_Capability;


$router = Router::singleton();

$router->get( 'roles', 'SL/Role/Controllers/Role_Controller@index' )
->permission(['SL\Core\Permissions\Authentic']);
$router->post( 'roles', 'SL/Role/Controllers/Role_Controller@store' )
->permission(['SL\Core\Permissions\Project_Manage_Capability'])
->validator( 'SL\Role\Validators\Create_Role' );

$router->get( 'roles/{id}', 'SL/Role/Controllers/Role_Controller@show' )
->permission(['SL\Core\Permissions\Authentic']);
$router->put( 'roles/{id}', 'SL/Role/Controllers/Role_Controller@update' )
->permission(['SL\Core\Permissions\Project_Manage_Capability'])
->validator( 'SL\Role\Validators\Update_Role' );

$router->delete( 'roles/{id}', 'SL/Role/Controllers/Role_Controller@destroy' )
->permission(['SL\Core\Permissions\Project_Manage_Capability']);
