<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once 'vendor/autoload.php';

BigTB\SuperLogistics\Setup\CustomRoles::init();
BigTB\SuperLogistics\Setup\Frontend::init();
BigTB\SuperLogistics\Setup\ORM::init();
