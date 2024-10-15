<?php
/**
 * Plugin Name: Super Logistics
 * Description: Logistics plugin which creates internal and customer facing logistics app.
 * Author: BigTB
 * Author URI: https://bigtb.com
 * Version: 1.1.0
 * Text Domain: super-logistics
 * Domain Path: /languages
 * License: GPL2
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require __DIR__.'/bootstrap/loaders.php';
require __DIR__.'/libs/configurations.php';

if ( version_compare( phpversion(), '5.6.0', '<' ) ) {
    add_action( 'admin_notices',  'sl_php_version_notice'  );
    return;
}

define( 'SL_FILE', __FILE__ );
define( 'SL_BASENAME', plugin_basename(__FILE__) );
define( 'SL_PLUGIN_ASSEST', plugins_url( 'views/assets', __FILE__ ) );

require __DIR__.'/bootstrap/start.php';
