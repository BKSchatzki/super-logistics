<?php

use SL\Core\Router\Router;
use SL\Core\Permissions\Administrator;
use SL\Core\Permissions\Authentic;
use SL\Core\Permissions\Access_Project;
use SL\Core\Permissions\Project_Create_Capability;
use SL\Project\Sanitizers\Project_Sanitizer;
use SL\Project\Validators\Create_Project;
use SL\Project\Validators\Update_Project;
use SL\Project\Sanitizers\Delete_Sanitizer;
use SL\Helper\Project;

$router = Router::singleton();

$router->get( 'advanced/projects', 'SL/Project/Controllers/Project_Controller@index' )
    ->permission(['SL\Core\Permissions\Authentic']);

$router->get( 'projects', 'SL/Project/Helper/Project@get_projects' )
    ->permission(['SL\Core\Permissions\Authentic']);

$router->get( 'projects/{id}', 'SL/Project/Controllers/Project_Controller@show' )
    ->permission(['SL\Core\Permissions\Access_Project']);

$router->post( 'projects', 'SL/Project/Controllers/Project_Controller@store' )
    ->permission(['SL\Core\Permissions\Project_Create_Capability'])
    ->validator( 'SL\Project\Validators\Create_Project' )
    ->sanitizer( 'SL\Project\Sanitizers\Project_Sanitizer' );

$router->post( 'projects/{id}/update', 'SL/Project/Controllers/Project_Controller@update' )
    ->permission(['SL\Core\Permissions\Project_Manage_Capability'])
    ->sanitizer( 'SL\Project\Sanitizers\Project_Sanitizer' )
    ->validator( 'SL\Project\Validators\Update_Project' );

$router->post( 'projects/{id}/favourite', 'SL/Project/Controllers/Project_Controller@favourite_project' )
    ->permission(['SL\Core\Permissions\Access_Project']);

$router->post( 'projects/{id}/delete', 'SL/Project/Controllers/Project_Controller@destroy' )
    ->sanitizer( 'SL\Project\Sanitizers\Delete_Sanitizer' )
    ->permission(['SL\Core\Permissions\Project_Manage_Capability']);

// $router->get( 'projects/search', 'SL/Project/Controllers/Project_Controller@project_search' )
//     ->permission(['SL\Core\Permissions\Authentic']);

