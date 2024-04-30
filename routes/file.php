<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'projects/{project_id}/files', 'SL/File/Controllers/File_Controller@index' )
    ->permission( ['SL\Core\Permissions\Access_Project'] );

$router->post( 'projects/{project_id}/files', 'SL/File/Controllers/File_Controller@store' )
    ->permission( ['SL\Core\Permissions\Create_File'] )
    ->sanitizer( 'SL\File\Sanitizers\File_Sanitizer' );

$router->get( 'projects/{project_id}/files/{file_id}', 'SL/File/Controllers/File_Controller@show' )
    ->permission( ['SL\Core\Permissions\Access_Project'] );

$router->post( 'projects/{project_id}/files/{file_id}/update', 'SL/File/Controllers/File_Controller@rename' )
    ->permission( ['SL\Core\Permissions\Edit_File'] )
    ->sanitizer( 'SL\File\Sanitizers\File_Sanitizer' );

$router->post( 'projects/{project_id}/files/{file_id}/delete', 'SL/File/Controllers/File_Controller@destroy' )
    ->permission( ['SL\Core\Permissions\Edit_File'] );

$router->get( 'projects/{project_id}/files/{file_id}/users/{user_id}/download', 'SL/File/Controllers/File_Controller@download' )
    ->permission( ['SL\Core\Permissions\Access_Project'] );

$router->get( 'get-mime-type-icon', 'SL/File/Controllers/File_Controller@get_mime_type_icon' );
