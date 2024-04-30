<?php

use SL\Core\Router\Router;
use SL\Core\Permissions\Administrator;
use SL\Core\Permissions\Access_Project;

$access_project = '\SL\Core\Permissions\Access_Project';
$authentic      = 'SL\Core\Permissions\Authentic';
$router         = Router::singleton();

$router->get( 'projects/{project_id}/activities', 'SL/Activity/Controllers/Activity_Controller@index' )
    ->permission( [ $access_project ] );

$router->get( 'activities', 'SL/Activity/Helper/Activity@get_activities' )
    ->permission( [ $authentic ] );
