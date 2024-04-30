<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'projects/{project_id}/milestones', 'SL/Milestone/Controllers/Milestone_Controller@index' )
    ->permission( ['SL\Core\Permissions\Access_Project'] );

$router->post( 'projects/{project_id}/milestones', 'SL/Milestone/Controllers/Milestone_Controller@store' )
    ->permission( ['SL\Core\Permissions\Create_Milestone'] )
    ->validator( 'SL\Milestone\Validators\Create_Milestone' )
    ->sanitizer( 'SL\Milestone\Sanitizers\Milestone_Sanitizer' );

$router->get( 'projects/{project_id}/milestones/{milestone_id}', 'SL/Milestone/Controllers/Milestone_Controller@show' )
    ->permission( ['SL\Core\Permissions\Access_Project'] );

$router->post( 'projects/{project_id}/milestones/{milestone_id}/update', 'SL/Milestone/Controllers/Milestone_Controller@update' )
    ->permission( ['SL\Core\Permissions\Edit_Milestone'] )
    ->validator( 'SL\Milestone\Validators\Create_Milestone' )
    ->sanitizer( 'SL\Milestone\Sanitizers\Milestone_Sanitizer' );

$router->post( 'projects/{project_id}/milestones/{milestone_id}/delete', 'SL/Milestone/Controllers/Milestone_Controller@destroy' )
    ->permission( ['SL\Core\Permissions\Edit_Milestone'] );

$router->post( 'projects/{project_id}/milestones/privacy/{milestone_id}', 'SL/Milestone/Controllers/Milestone_Controller@privacy' )
	->permission( ['SL\Core\Permissions\Edit_Milestone'] );
