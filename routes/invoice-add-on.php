<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'all-invoices', 'SL/Invoice/Controllers/Invoice_Controller@show_all' )
    ->permission( ['SL\Core\Permissions\Access_Project'] );
