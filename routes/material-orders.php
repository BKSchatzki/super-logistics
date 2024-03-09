<?php

use WeDevs\PM\Core\Router\Router;
use WeDevs\PM\Core\Permissions\Access_Project;
use WeDevs\PM\Core\Permissions\Project_Manage_Capability;
use WeDevs\PM\Core\Permissions\Create_Task;

$router = Router::singleton();
$generic_permissions = ['WeDevs\PM\Core\Permissions\Authentic'];

$router->get( 'material-orders', 'WeDevs/PM/Global_Kanboard/Controllers/Global_Kanboard_Controller@index' )
    ->permission($generic_permissions);

$router->get( 'material-orders/vendors', 'WeDevs/PM/Global_Kanboard/Controllers/Global_Kanboard_Controller@create' )
    ->permission($generic_permissions);
