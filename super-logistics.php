<?php
/*
 * Plugin Name: Super Logistics
 * Description: A plugin for managing logistics, providing tools for managing shipments, tracking, and inventory.
 * Version: 3.0.0
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
	BigTB\SL\Setup\WP\CustomRoles::init();
	BigTB\SL\Setup\WP\FrontendManager::init();
	BigTB\SL\Setup\WP\ScriptManager::init( __FILE__ );
	BigTB\SL\Setup\DB\TableManager::init();
	BigTB\SL\Setup\DB\ORM::init();
} );

add_action( 'rest_api_init', function () {
    $config = new BigTB\SL\Setup\Routing\RouteConfig();
	$routes = $config->routes;
	BigTB\SL\Setup\Routing\RouteManager::declareRoutes( $routes );
} );