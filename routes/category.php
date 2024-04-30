<?php

use SL\Core\Router\Router;

$router                    = Router::singleton();
$project_manage_capability = 'SL\Core\Permissions\Project_Manage_Capability';
$authentic                 = 'SL\Core\Permissions\Authentic';

$router->get( 'categories', 'SL/Category/Controllers/Category_Controller@index' )
    ->permission( [ $authentic ] );

$router->post( 'categories', 'SL/Category/Controllers/Category_Controller@store' )
    ->permission( ['SL\Core\Permissions\Categories_Page_Access'] )
    ->validator( 'SL\Category\Validators\Create_Category' )
    ->sanitizer( 'SL\Category\Sanitizers\Category_Sanitizer' );

$router->get( 'categories/{id}', 'SL/Category/Controllers/Category_Controller@show' )
    ->permission( [ $authentic ] );

$router->post( 'categories/{id}/update', 'SL/Category/Controllers/Category_Controller@update' )
    ->permission( ['SL\Core\Permissions\Categories_Page_Access'] )
    ->validator( 'SL\Category\Validators\Update_Category' )
    ->sanitizer( 'SL\Category\Sanitizers\Category_Sanitizer' );

$router->post( 'categories/{id}/delete', 'SL/Category/Controllers/Category_Controller@destroy' )
    ->permission( ['SL\Core\Permissions\Categories_Page_Access'] );

$router->post( 'categories/bulk-delete', 'SL/Category/Controllers/Category_Controller@bulk_destroy' )
    ->permission( ['SL\Core\Permissions\Categories_Page_Access'] );
