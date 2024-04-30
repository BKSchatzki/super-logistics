<?php

use SL\Core\Router\Router;
use SL\Core\Permissions\Authentic;
$router = Router::singleton();

$router->get( 'trello', 'SL/Imports/Controllers/Trello_Controller@index' );
$router->post( 'trello', 'SL/Imports/Controllers/Trello_Controller@index' );

$router->get( 'trello/test', 'SL/Imports/Controllers/Trello_Controller@test' );
$router->post( 'trello/test', 'SL/Imports/Controllers/Trello_Controller@test' );


$router->get( 'trello/get_user', 'SL/Imports/Controllers/Trello_Controller@get_user' );
$router->post( 'trello/get_user', 'SL/Imports/Controllers/Trello_Controller@get_user' );


$router->get( 'trello/get_boards', 'SL/Imports/Controllers/Trello_Controller@get_boards' );
$router->post( 'trello/get_boards', 'SL/Imports/Controllers/Trello_Controller@get_boards' );

$router->get( 'trello/get_lists', 'SL/Imports/Controllers/Trello_Controller@get_lists' );
$router->post( 'trello/get_lists', 'SL/Imports/Controllers/Trello_Controller@get_lists' );

$router->get( 'trello/get_cards', 'SL/Imports/Controllers/Trello_Controller@get_cards' );
$router->post( 'trello/get_cards', 'SL/Imports/Controllers/Trello_Controller@get_cards' );

$router->get( 'trello/get_subcards', 'SL/Imports/Controllers/Trello_Controller@get_subcards' );
$router->post( 'trello/get_subcards', 'SL/Imports/Controllers/Trello_Controller@get_subcards' );

$router->get( 'trello/get_users', 'SL/Imports/Controllers/Trello_Controller@get_users' );
$router->post( 'trello/get_users', 'SL/Imports/Controllers/Trello_Controller@get_users' );
