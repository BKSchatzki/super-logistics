<?php

namespace BigTB\SL\Setup\WP;

class FrontendManager
{
    public static function init(): void {
        add_shortcode( 'super-logistics', __CLASS__ . '::shortcode' );
    }

    public static function shortcode(): string {
        return '<div id="super-logistics-app">';
    }
}