<?php

use WeDevs\PM\Core\Router\Router;
use WeDevs\PM\Core\Permissions\Access_Project;
use WeDevs\PM\Core\Permissions\Project_Manage_Capability;
use WeDevs\PM\Core\Permissions\Create_Task;

$router = Router::singleton();
$generic_permissions = ['WeDevs\PM\Core\Permissions\Authentic'];

$router->get( 'global-kanboard', 'WeDevs/PM/Global_Kanboard/Controllers/Global_Kanboard_Controller@index' )
    ->permission($generic_permissions);

// Adds a new board (column) to the global kanboard
$router->post( 'global-kanboard',
    'WeDevs\PM\Global_Kanboard\Controllers\Global_Kanboard_Controller@store' )
    ->permission($generic_permissions);

// Adds a new board (column) to the global kanboard
$router->delete( 'global-kanboard/{board_id}',
    'WeDevs\PM\Global_Kanboard\Controllers\Global_Kanboard_Controller@destroy' )
    ->permission($generic_permissions);

// Adds an existing project to the global kanboard
$router->post( 'global-kanboard/{board_id}/project/{project_id}',
    'WeDevs\PM\Global_Kanboard\Controllers\Global_Kanboard_Controller@store_searchable_project' )
	->permission($generic_permissions);

// meant to gather all the projects that are searchable to be placed in the global kanboard
$router->get( 'global-kanboard/projects',
    'WeDevs\PM\Global_Kanboard\Controllers\Global_Kanboard_Controller@get_projects' )
    ->permission($generic_permissions);

// show() retrieves all of the projects on the boardable table to be displayed on the global kanboard
$router->get( 'global-kanboard/{board_id}/projects',
    'WeDevs\PM\Global_Kanboard\Controllers\Global_Kanboard_Controller@show' )
    ->permission($generic_permissions);

// removes the boardable from a particular board - and from the kanban altogether
$router->delete( 'global-kanboard/{board_id}/boardable/{project_id}',
    'WeDevs\PM\Global_Kanboard\Controllers\Global_Kanboard_Controller@remove_boardable' )
    ->permission($generic_permissions);

// updates the boardables to be located on a particular board by editing board column
$router->put( 'global-kanboard/boardable',
    'WeDevs\PM\Global_Kanboard\Controllers\Global_Kanboard_Controller@update_boardable' )
    ->permission($generic_permissions);
// Everything edited above this point
// Everything old below this point

$router->post( 'projects/{project_id}/kanboard/{board_id}/update', 'WeDevs\PM_Pro\Modules\Kanboard\Src\Controllers\Kanboard_Controller@update' )
    ->permission( ['WeDevs\PM\Core\Permissions\Project_Manage_Capability'] );

$router->post( 'projects/{project_id}/kanboard/board-order', 'WeDevs\PM_Pro\Modules\Kanboard\Src\Controllers\Kanboard_Controller@board_order' )
    ->permission( ['WeDevs\PM\Core\Permissions\Project_Manage_Capability'] );

$router->post( 'projects/{project_id}/kanboard/task-order', 'WeDevs\PM_Pro\Modules\Kanboard\Src\Controllers\Kanboard_Controller@task_order' )
    ->permission( ['WeDevs\PM\Core\Permissions\Access_Project'] );

$router->post( 'projects/{project_id}/kanboard/{board_id}/delete', 'WeDevs\PM_Pro\Modules\Kanboard\Src\Controllers\Kanboard_Controller@destroy' )
    ->permission( ['WeDevs\PM\Core\Permissions\Project_Manage_Capability'] );

$router->post( 'projects/{project_id}/kanboard/{board_id}/tasks/{task_id}/delete', 'WeDevs\PM_Pro\Modules\Kanboard\Src\Controllers\Kanboard_Controller@delte_task' )
    ->permission( ['WeDevs\PM\Core\Permissions\Delete_Task'] );

$router->post( 'projects/{project_id}/list-view-type', 'WeDevs\PM_Pro\Modules\Kanboard\Src\Controllers\Kanboard_Controller@list_view_type' )
    ->permission( ['WeDevs\PM\Core\Permissions\Access_Project'] );

$router->post( 'projects/{project_id}/boards/{board_id}/automation', 'WeDevs\PM_Pro\Modules\Kanboard\Src\Controllers\Kanboard_Controller@automation' )
    ->permission( ['WeDevs\PM\Core\Permissions\Access_Project'] );

$router->post( 'projects/{project_id}/kanboard/{board_id}/header_background', 'WeDevs\PM_Pro\Modules\Kanboard\Src\Controllers\Kanboard_Controller@header_background' )
    ->permission( ['WeDevs\PM\Core\Permissions\Access_Project'] );

$router->post( 'projects/{project_id}/kanboard/filter', 'WeDevs\PM_Pro\Modules\Kanboard\Src\Controllers\Kanboard_Controller@search_tasks' );

$router->post( 'projects/{project_id}/kanboard/import-tasks', 'WeDevs\PM_Pro\Modules\Kanboard\Src\Controllers\Kanboard_Controller@import_bulk_task' )
    ->permission( ['WeDevs\PM\Core\Permissions\Access_Project'] );



