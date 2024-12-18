<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once 'vendor/autoload.php';

BigTB\SL\Setup\CustomRoles::init();
BigTB\SL\Setup\FrontendManager::init();
BigTB\SL\Setup\ScriptManager::init();
BigTB\SL\Setup\ORM::init();
