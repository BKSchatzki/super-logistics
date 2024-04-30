<?php

namespace SL\Core\WP;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Shortcodes {

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		if ( is_admin() ) {
			return;
		}
		$shortcodes = array(
			'super-logistics-external'  => __CLASS__ . '::external_shortcode',
			'super-logistics-internal'  => __CLASS__ . '::internal_shortcode',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @param string[] $function
	 * @param array $atts (default: array())
	 * @return string
	 */
	public static function shortcode_wrapper(
		$function,
		$atts    = array(),
		$wrapper = array(
			'class'  => 'pm-pro-wrap',
			'before' => null,
			'after'  => null
		)
	) {
		ob_start();

		echo empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		call_user_func( $function, $atts );
		echo empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];

		return ob_get_clean();
	}

	/**
	 * shortcode for external users.
	 *
	 * @param mixed $atts
	 *
	 * @return string
	 */
	public static function internal_shortcode( $atts ) {
		return self::shortcode_wrapper(
			array( 'SL\\Core\\Shortcodes\\SL_Shortcode', 'output-internal' ),
			$atts
		);
	}

	/**
	 * shortcode for internal users.
	 *
	 * @param mixed $atts
	 *
	 * @return string
	 */
	public static function external_shortcode( $atts ) {
		return self::shortcode_wrapper(
			array( 'SL\\Core\\Shortcodes\\SL_Shortcode', 'output-external' ),
			$atts
		);
	}
}
