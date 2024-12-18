<?php

namespace BigTB\SL\Setup;

class FrontendManager
{
    public static function init() {
        add_shortcode( 'super-logistics', __CLASS__ . '::shortcode' );
    }

    private static function shortcode() {
        return '<div id="super-logistics-app">';
    }
}