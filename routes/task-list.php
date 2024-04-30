<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'projects/{project_id}/task-lists', 'SL/Task_List/Controllers/Task_List_Controller@index' )
	->permission( ['SL\Core\Permissions\Access_Project'] );

$router->get( 'advanced/{project_id}/task-lists', 'SL\Task_List\Helper\Task_List@get_task_lists' )
    ->permission( ['SL\Core\Permissions\Access_Project'] );

$router->post( 'projects/{project_id}/task-lists', 'SL/Task_List/Controllers/Task_List_Controller@store' )
	->permission( ['SL\Core\Permissions\Create_Task_List'] )
	->validator( 'SL\Task_List\Validators\Create_Task_List' )
	->sanitizer( 'SL\Task_List\Validators\Task_List_Sanitizer' );

$router->get( 'projects/{project_id}/task-lists/{task_list_id}', 'SL/Task_List/Controllers/Task_List_Controller@show' )
	->permission( ['SL\Core\Permissions\Access_Project'] );

$router->post( 'projects/{project_id}/task-lists/{task_list_id}/update', 'SL/Task_List/Controllers/Task_List_Controller@update' )
	->permission( ['SL\Core\Permissions\Edit_Task_List'] )
	->validator( 'SL\Task_List\Validators\Update_Task_List' )
	->sanitizer( 'SL\Task_List\Validators\Task_List_Sanitizer' );

$router->post( 'projects/{project_id}/task-lists/{task_list_id}/delete', 'SL/Task_List/Controllers/Task_List_Controller@destroy' )
	->permission( ['SL\Core\Permissions\Edit_Task_List'] );

$router->put( 'projects/{project_id}/task-lists/{task_list_id}/attach-users', 'SL/Task_List/Controllers/Task_List_Controller@attach_users' )
	->permission( ['SL\Core\Permissions\Edit_Task_List'] );

$router->put( 'projects/{project_id}/task-lists/{task_list_id}/detach-users', 'SL/Task_List/Controllers/Task_List_Controller@detach_users' )
	->permission( ['SL\Core\Permissions\Edit_Task_List'] );

$router->post( 'projects/{project_id}/task-lists/privacy/{task_list_id}', 'SL/Task_List/Controllers/Task_List_Controller@privacy' )
	->permission( ['SL\Core\Permissions\Edit_Task_List'] );

$router->post( 'projects/{project_id}/lists/sorting', 'SL/Task_List/Controllers/Task_List_Controller@list_sorting' )
	->permission( ['SL\Core\Permissions\Project_Manage_Capability'] );

$router->get( 'projects/{project_id}/lists/search', 'SL/Task_List/Controllers/Task_List_Controller@list_search' )
	->permission( ['SL\Core\Permissions\Access_Project'] );
