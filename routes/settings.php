<?php

use SL\Core\Router\Router;

$router    = Router::singleton();
$authentic = 'SL\Core\Permissions\Authentic';

$router->get( 'settings', 'SL/Settings/Controllers/Settings_Controller@index' )
    ->permission( [ $authentic ] );

$router->post( 'settings', 'SL/Settings/Controllers/Settings_Controller@store' )
    ->permission( ['SL\Core\Permissions\Settings_Page_Access'] );

$router->post( 'settings/notice', 'SL/Settings/Controllers/Settings_Controller@notice' )
    ->permission( [ $authentic ] );
//$router->get( 'projects/settings/{key}/key', 'SL/Settings/Controllers/Settings_Controller@pluck_without_project' );;

//$router->get( 'projects/{project_id}/settings/{key}/key', 'SL/Settings/Controllers/Settings_Controller@pluck_with_project' );;
$router->get( 'projects/{project_id}/settings', 'SL/Settings/Controllers/Settings_Controller@index' )
    ->permission( [ $authentic ] );

$router->post( 'projects/{project_id}/settings', 'SL/Settings/Controllers/Settings_Controller@store' )
    ->permission( ['SL\Core\Permissions\Project_Settings_Page_Access'] );

$router->post( 'projects/{project_id}/delete/{id}/settings', 'SL/Settings/Controllers/Settings_Controller@destroy' )
    ->permission( ['SL\Core\Permissions\Project_Settings_Page_Access'] );

$router->post( 'settings/task-types', 'SL/Settings/Controllers/Task_Types_Controller@store' )
    ->permission( ['SL\Core\Permissions\Settings_Page_Access'] )
    ->sanitizer( 'SL\Settings\Sanitizers\Task_Type_Sanitizer' );

$router->get( 'settings/task-types', 'SL/Settings/Controllers/Task_Types_Controller@index' )
    ->permission( [ $authentic ] );

$router->post( 'settings/task-types/{id}', 'SL/Settings/Controllers/Task_Types_Controller@update_task_type' )
    ->permission( ['SL\Core\Permissions\Settings_Page_Access'] )
    ->sanitizer( 'SL\Settings\Sanitizers\Task_Type_Sanitizer' );

$router->post( 'settings/task-types/{id}/delete', 'SL/Settings/Controllers/Task_Types_Controller@destroy_task_type' )
    ->permission( ['SL\Core\Permissions\Settings_Page_Access'] );

