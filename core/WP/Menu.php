<?php

namespace SL\Core\WP;

use SL\Core\WP\Output as Output;
use SL\Core\WP\Enqueue_Scripts as Enqueue_Scripts;
use SL\User\Models\User;
use SL\User\Models\User_Role;

class Menu {

	private static $capability = 'read';

    public static function admin_menu() {
        global $submenu, $wedevs_pm_pro, $wedevs_license_progress;

        $slug = pm_admin_slug();

        $home = add_menu_page(
            'Super Logistics',
            'Super Logistics',
            self::$capability, $slug, array( new Output, 'home_page'
            ), self::pm_svg(), 3 );

        $submenu[$slug][] = ['Lookup', self::$capability, "admin.php?page={$slug}#/lookup" ];
        $submenu[$slug][] = ['Receiving', self::$capability, "admin.php?page={$slug}#/input" ];
        $submenu[$slug][] = ['Labels', self::$capability, "admin.php?page={$slug}#/labels" ];

        do_action( 'pm_menu_before_load_scripts', $home );

        add_action( 'admin_print_styles-' . $home, array( 'SL\\Core\\WP\\Menu', 'scripts' ) );

        do_action( 'cpm_admin_menu', self::$capability, $home );

        if ( pm_has_admin_capability() ) {
            $submenu[$slug][] = [ 'Settings', self::$capability, "admin.php?page={$slug}#/settings" ];
        }

        do_action( 'pm_menu_after_load_scripts', $home );
    }

	public static function pm_svg() {
		return '';
    }

	public static function scripts() {
		Enqueue_Scripts::scripts();
		Enqueue_Scripts::styles();
	}
}
