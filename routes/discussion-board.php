<?php

use SL\Core\Router\Router;

$router         = Router::singleton();
$access_project = 'SL\Core\Permissions\Access_Project';
$create_discuss = 'SL\Core\Permissions\Create_Discuss';

$router->get( 'projects/{project_id}/discussion-boards', 'SL/Discussion_Board/Controllers/Discussion_Board_Controller@index' )
    ->permission( [ $access_project ] );

$router->post( 'projects/{project_id}/discussion-boards', 'SL/Discussion_Board/Controllers/Discussion_Board_Controller@store' )
    ->permission( [ $create_discuss ] )
    ->validator( 'SL\Discussion_Board\Validators\Create_Discussion_Board' )
    ->sanitizer( 'SL\Discussion_Board\Validators\Discussion_Board_Sanitizer' );

$router->get( 'projects/{project_id}/discussion-boards/{discussion_board_id}', 'SL/Discussion_Board/Controllers/Discussion_Board_Controller@show' )
    ->permission( [ $access_project ] );

$router->post( 'projects/{project_id}/discussion-boards/{discussion_board_id}', 'SL/Discussion_Board/Controllers/Discussion_Board_Controller@update' )
    ->permission( ['SL\Core\Permissions\Edit_Discuss'] )
    ->validator( 'SL\Discussion_Board\Validators\Create_Discussion_Board' )
    ->sanitizer( 'SL\Discussion_Board\Validators\Discussion_Board_Sanitizer' );

$router->post( 'projects/{project_id}/discussion-boards/privacy/{discussion_board_id}', 'SL/Discussion_Board/Controllers/Discussion_Board_Controller@privacy' )
	->permission( ['SL\Core\Permissions\Edit_Discuss'] );

$router->post( 'projects/{project_id}/discussion-boards/{discussion_board_id}/delete', 'SL/Discussion_Board/Controllers/Discussion_Board_Controller@destroy' )
	->permission( ['SL\Core\Permissions\Edit_Discuss'] );

$router->put( 'projects/{project_id}/discussion-boards/{discussion_board_id}/attach-users', 'SL/Discussion_Board/Controllers/Discussion_Board_Controller@attach_users' )->permission( ['SL\Core\Permissions\Edit_Discuss'] );

$router->put( 'projects/{project_id}/discussion-boards/{discussion_board_id}/detach-users', 'SL/Discussion_Board/Controllers/Discussion_Board_Controller@detach_users' )->permission( ['SL\Core\Permissions\Edit_Discuss'] );
