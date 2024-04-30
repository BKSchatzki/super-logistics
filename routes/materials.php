<?php

use SL\Core\Router\Router;
use SL\Core\Permissions\Access_Project;
use SL\Core\Permissions\Project_Manage_Capability;
use SL\Core\Permissions\Create_Task;

$router = Router::singleton();
$generic_permissions = ['SL\Core\Permissions\Authentic'];

$router->get( 'materials/orders', 'SL/Materials/Controllers/MaterialOrderController@show' )
    ->permission($generic_permissions);

$router->get( 'materials/vendors', 'SL/Materials/Controllers/MaterialVendorController@show' )
    ->permission($generic_permissions);

$router->post( 'materials/orders', 'SL/Materials/Controllers/MaterialOrderController@store' )
    ->permission($generic_permissions);

$router->post( 'materials/vendors', 'SL/Materials/Controllers/MaterialVendorController@store' )
    ->permission($generic_permissions);

$router->delete( 'materials/orders/{id}', 'SL/Materials/Controllers/MaterialOrderController@delete' )
    ->permission($generic_permissions);

$router->delete( 'materials/vendors/{id}', 'SL/Materials/Controllers/MaterialVendorController@delete' )
    ->permission($generic_permissions);
