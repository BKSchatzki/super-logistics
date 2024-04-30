<?php

use SL\Core\Router\Router;
use SL\Core\Permissions\Authentic;
use SL\Core\Permissions\Project_Manage_Capability;

$router    = Router::singleton();
$authentic = 'SL\Core\Permissions\Authentic';

$router->get( 'search', 'SL\Search\Controllers\Search_Controller@search' )
    ->permission( [ $authentic ] );

$router->get( 'admin-topbar-search', 'SL\Search\Controllers\Search_Controller@searchTopBar' )
    ->permission( [ $authentic ] );
