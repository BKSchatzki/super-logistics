<?php

use SL\Core\Router\Router;

$router = Router::singleton();

$router->get( 'reports/trailer-manifest', 'SL/Report/Controllers/ReportsController@getTrailerManifest' );

$router->get( 'reports/pallet-manifest', 'SL/Report/Controllers/ReportsController@getPalletManifest' );

$router->get( 'reports/show-report', 'SL/Report/Controllers/ReportsController@getShowReport' );

$router->get( 'reports/show-report-two', 'SL/Report/Controllers/ReportsController@getShowReportTwo' );
