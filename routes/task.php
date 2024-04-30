<?php

use SL\Core\Router\Router;
use SL\Core\Permissions\Access_Project;
use SL\Core\Permissions\Create_Task;
use SL\Core\Permissions\Administrator;

$router    = Router::singleton();
$authentic = 'SL\Core\Permissions\Authentic';
$accPermission = 'SL\Core\Permissions\Access_Project';
$editPermission = 'SL\Core\Permissions\Edit_Task';

// Get tasks for specified project
$router->get( 'projects/{project_id}/tasks', 'SL/Task/Controllers/Task_Controller@index' )
    ->permission([$accPermission]);

// Get tasks depending on request params - could be none?
$router->get( 'tasks', 'SL/Task/Helper/Task@get_tasks' )
    ->permission( [ $authentic ] );

$router->get( 'advanced/tasks', 'SL/Task/Helper/Task@get_tasks' )
    ->permission( [ $authentic ] );

$router->get( 'advanced/taskscsv', 'SL/Task/Helper/Task@get_taskscsv' )
    ->permission( [ $authentic ] );

$router->post( 'projects/{project_id}/tasks', 'SL/Task/Controllers/Task_Controller@store' )
    ->permission(['SL\Core\Permissions\Create_Task'])
    ->validator( 'SL\Task\Validators\Create_Task' )
    ->sanitizer( 'SL\Task\Sanitizers\Task_Sanitizer' );

$router->post( 'projects/{project_id}/tasks/sorting', 'SL/Task/Controllers/Task_Controller@task_sorting' )
    ->permission( [ $authentic ] );

$router->get( 'projects/{project_id}/tasks/{task_id}', 'SL/Task/Controllers/Task_Controller@show' )
    ->permission([$accPermission]);

$router->post( 'projects/{project_id}/tasks/{task_id}/update', 'SL/Task/Controllers/Task_Controller@update' )
    ->permission([$editPermission])
    ->validator( 'SL\Task\Validators\Create_Task' )
    ->sanitizer( 'SL\Task\Sanitizers\Task_Sanitizer' );

$router->post( 'projects/{project_id}/tasks/{task_id}/change-status', 'SL/Task/Controllers/Task_Controller@change_status' )
    ->permission(['SL\Core\Permissions\Complete_Task']);

$router->post( 'projects/{project_id}/tasks/{task_id}/delete', 'SL/Task/Controllers/Task_Controller@destroy' )
    ->permission([$editPermission]);

$router->put( 'projects/{project_id}/tasks/{task_id}/attach-users', 'SL/Task/Controllers/Task_Controller@attach_users' )
    ->permission([$editPermission]);

$router->put( 'projects/{project_id}/tasks/{task_id}/detach-users', 'SL/Task/Controllers/Task_Controller@detach_users' )
    ->permission([$editPermission]);

$router->put( 'projects/{project_id}/tasks/{task_id}/boards', 'SL/Task/Controllers/Task_Controller@attach_to_board' )
    ->permission([$editPermission]);

$router->delete( 'projects/{project_id}/tasks/{task_id}/boards', 'SL/Task/Controllers/Task_Controller@detach_from_board' )
    ->permission([$editPermission]);

$router->put( 'projects/{project_id}/tasks/reorder', 'SL/Task/Controllers/Task_Controller@reorder' )
    ->permission(['SL\Core\Permissions\Project_Manage_Capability']);

$router->post( 'projects/{project_id}/tasks/privacy/{task_id}', 'SL/Task/Controllers/Task_Controller@privacy' )
    ->permission([$editPermission]);

$router->post( 'projects/{project_id}/tasks/filter', 'SL/Task/Controllers/Task_Controller@filter' )
    ->permission([$accPermission]);

$router->post( 'projects/{project_id}/tasks/{task_id}/activity', 'SL/Task/Controllers/Task_Controller@activities' )
    ->permission([$accPermission]);

$router->post( 'tasks/{task_id}/duplicate', 'SL/Task/Controllers/Task_Controller@duplicate' )
    ->permission([$editPermission]);

$router->get( 'projects/{project_id}/task-lists/{list_id}/more/tasks', 'SL/Task/Controllers/Task_Controller@load_more_tasks' )
    ->permission([$accPermission]);





