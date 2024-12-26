<?php
/*
 * Plugin Name: Super Logistics
 * Description: A plugin for managing logistics, providing tools for managing shipments, tracking, and inventory.
 * Version: 2.0.0
 * Author: BigTB
 * Author URI: https://bigtb.com
 * License: Proprietary
 * Text Domain: super-logistics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'vendor/autoload.php';

add_action( 'init', function () {
	BigTB\SL\Setup\CustomRoles::init();
	BigTB\SL\Setup\FrontendManager::init();
	BigTB\SL\Setup\ScriptManager::init( __FILE__ );
	BigTB\SL\Setup\DB\TableManager::init();
	BigTB\SL\Setup\DB\ORM::init();
} );

add_action( 'rest_api_init', function () {
	$routes = require_once 'src/setup/routing/routes.php';
	BigTB\SL\Setup\Routing\RouteManager::declareRoutes( $routes );
} );