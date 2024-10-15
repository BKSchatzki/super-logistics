<?php
namespace SL\Core\Shortcodes;

use SL\Core\WP\Enqueue_Scripts;
use SL\Core\WP\Register_Scripts;

/**
 */
class SL_Shortcode {

	/**
	 * Get the shortcode content.
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function get( $atts ) {
		return Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
     * @param array $atts
     */
	public static function output(array $atts) {
        if ( ! is_user_logged_in() ) {
            wp_login_form( array( 'echo' => true ) );

            return;
        }
		echo pm_root_element();
		self::scripts();

	}

    public static function public_output() {
        echo pm_public_root_element();
        self::scripts();
    }

	public static function scripts() {
        wp_enqueue_script(
            'pm-hooks',
            pm_config('frontend.assets_url') . 'vendor/wp-hooks/pm-hooks.js',
            '',
            false,
            false
        );

        Register_Scripts::scripts();
        Register_Scripts::styles();

        do_action( "pm_load_shortcode_script" );

        wp_enqueue_style( 'pm-frontend-style' );
        wp_enqueue_script('pm-frontend-scripts');

        // free scripts
        Enqueue_Scripts::scripts();
        Enqueue_Scripts::styles();
	}

}
