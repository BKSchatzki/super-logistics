<?php

use SL\Core\Router\Router;
use SL\Core\Permissions\Authentic;
use SL\Core\Permissions\Access_Project;

$router  = Router::singleton();
$access  = 'SL\Core\Permissions\Access_Project';

$router->get( 'projects/{project_id}/comments', 'SL/Comment/Controllers/Comment_Controller@index' )
    ->permission([$access]);

$router->post( 'projects/{project_id}/comments', 'SL/Comment/Controllers/Comment_Controller@store' )
    ->permission([$access])
    ->validator( 'SL\Comment\Validators\Create_Comment' )
    ->sanitizer( 'SL\Comment\Validators\Comment_Sanitizer' );


$router->get( 'projects/{project_id}/comments/{comment_id}', 'SL/Comment/Controllers/Comment_Controller@show' )
    ->permission([$access]);

$router->post( 'projects/{project_id}/comments/{comment_id}', 'SL/Comment/Controllers/Comment_Controller@update' )
    ->permission(['SL\Core\Permissions\Edit_Comment'])
    ->validator( 'SL\Comment\Validators\Create_Comment' )
    ->sanitizer( 'SL\Comment\Validators\Comment_Sanitizer' );


$router->post( 'projects/{project_id}/comments/{comment_id}/delete', 'SL/Comment/Controllers/Comment_Controller@destroy' )
    ->permission(['SL\Core\Permissions\Edit_Comment']);
