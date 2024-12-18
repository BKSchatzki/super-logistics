<?php

namespace BigTB\SuperLogistics\Setup;

class Frontend
{
    public static function init() {
        add_shortcode( 'super-logistics', __CLASS__ . '::shortcode' );
    }

    private static function shortcode() {
        return '<div id="super-logistics-app">';
    }
}