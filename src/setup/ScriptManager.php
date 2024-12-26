<?php

namespace BigTB\SL\Setup;

class ScriptManager {
	public static function init( $mainPluginDir ): void {
		add_action( 'wp_enqueue_scripts', function () use ( $mainPluginDir ) {
			self::enqueueScripts( $mainPluginDir );
		}, 10, 1 );

		add_filter( 'script_loader_tag', __CLASS__ . '::addScriptTags', 10, 3 );

		add_action( 'wp_enqueue_scripts', function () use ( $mainPluginDir ) {
			self::enqueueStyles( $mainPluginDir );
		}, 10, 1 );
	}

	public static function enqueueScripts( $mainPluginDir ): void {
		$frontend_js_path = plugin_dir_url( $mainPluginDir ) . 'view/dist/bundle.js';
		wp_enqueue_script( 'js-bundle', $frontend_js_path, [], false, true );

		wp_localize_script( 'js-bundle', 'localized', array(
			'baseURL' => rest_url( 'super-logistics' ),
			'nonce'   => wp_create_nonce( 'wp_rest' ),
			'pageURL' => get_permalink( get_the_ID() )
		) );
	}

	public static function enqueueStyles( $mainPluginDir ): void {
		$css_dir   = plugin_dir_path( $mainPluginDir ) . 'view/dist/';
		$css_files = glob( $css_dir . 'bundle*.css' );

		foreach ( $css_files as $css_file ) {
			$css_url = plugin_dir_url( $mainPluginDir ) . 'view/dist/' . basename( $css_file );
			error_log( 'Enqueuing style: ' . $css_url );
			wp_enqueue_style( 'css-' . basename( $css_file, '.css' ), $css_url, [], false, 'all' );
		}
	}

	public static function addScriptTags( $tag, $handle, $src ): string {
		// Check if the handle matches the script you want to modify
		if ( 'js-bundle' === $handle ) {
			// Add type="module" to the script tag
			$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
		}

		return $tag;
	}
}