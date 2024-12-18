<?php

namespace BigTB\SL\Setup;

class ScriptManager
{
    public static function init() {
        add_action( 'wp_enqueue_scripts', __CLASS__ . '::enqueueScripts' );
        add_action( 'wp_enqueue_styles', __CLASS__ . '::enqueueStyles' );
    }

    public static function enqueueScripts(): void {

        $frontend_js_path = plugins_url('/views/dist/bundle.js', __FILE__);
        wp_enqueue_script('js-bundle', $frontend_js_path, [], false, true);

        wp_localize_script('js-bundle', 'localized', array(
            'baseURL' => rest_url('super-logistics/'),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }

    public static function enqueueStyles(): void {
        $frontend_css_path = plugins_url('/views/dist/bundle.css', __FILE__);
        wp_enqueue_style('css-bundle', $frontend_css_path, [], false, 'all');
    }
}