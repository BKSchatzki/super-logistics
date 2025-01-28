<?php

namespace BigTB\SL\Setup\WP;

class ScriptManager {

	private static string $distPath = 'view/dist/';

	public static function init( $mainPluginDir ): void {
		add_action( 'wp_enqueue_scripts', function () use ( $mainPluginDir ) {
			self::enqueueScripts( $mainPluginDir );
		}, 10, 1 );

		add_filter( 'script_loader_tag', __CLASS__ . '::addScriptTags', 10, 3 );

		add_action( 'wp_enqueue_scripts', function () use ( $mainPluginDir ) {
			self::enqueueStyles( $mainPluginDir );
		}, 99999, 1 );
	}

	public static function enqueueScripts( $mainPluginDir ): void {
		$js_dir   = plugin_dir_path( $mainPluginDir ) . self::$distPath;
		$js_files = glob( $js_dir . 'bundle*.js' );

		foreach ( $js_files as $js_file ) {

			$handle = 'js-' . basename( $js_file );

			$js_url = plugin_dir_url( $mainPluginDir ) . self::$distPath . basename( $js_file );
			wp_enqueue_script( $handle, $js_url, [], false, true );

			wp_localize_script( $handle, 'localized', array(
				'baseURL'  => rest_url( 'super-logistics' ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
				'pageURL'  => get_permalink( get_the_ID() ),
				'loginURL' => wp_login_url()
			) );
		}
	}

	public static function enqueueStyles( $mainPluginDir ): void {
		$css_dir   = plugin_dir_path( $mainPluginDir ) . self::$distPath;
		$css_files = glob( $css_dir . '*.css' );

		foreach ( $css_files as $css_file ) {
			$css_url = plugin_dir_url( $mainPluginDir ) . self::$distPath . basename( $css_file );
			wp_enqueue_style( 'css-' . basename( $css_file, '.css' ), $css_url, [], false, 'all' );
		}
	}

	public static function addScriptTags( $tag, $handle, $src ): string {
		// Check if the handle matches the script you want to modify
		if ( str_contains( $handle, 'js-bundle' ) ) {
			// Add type="module" to the script tag
			$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
		}

		return $tag;
	}
}