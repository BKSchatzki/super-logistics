<?php

use WeDevs\PM\Core\Router\Router;

$router = Router::singleton();

$router->get( 'stages', 'WeDevs/PM/Stage/Helper/Stage@getAllStages' )
    ->permission( ['WeDevs\PM\Core\Permissions\Access_Project'] );

$router->get( 'stages/{stage_id}', 'WeDevs/PM/Stage/Helper/Stage@getProjectsAtStage' )
    ->permission( ['WeDevs\PM\Core\Permissions\Access_Project'] );
