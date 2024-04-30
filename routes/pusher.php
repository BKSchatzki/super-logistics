<?php
use SL\Core\Router\Router;
use SL\Core\Permissions\Access_Project;
use SL\Core\Permissions\Project_Manage_Capability;
use SL\Core\Permissions\Authentic;


$router = Router::singleton();


$router->post( 'user/{user_id}/pusher/auth', 'SL\Pusher\Src\Controllers\Pusher_Controller@authentication' )
    ->permission(['SL\Core\Permissions\Authentic']);



