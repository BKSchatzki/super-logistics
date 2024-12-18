<?php

namespace BigTB\SuperLogistics\Setup;

use \wpdb;
use Illuminate\Database\Capsule\Manager as Capsule;

class ORM
{
    public static function init(): void
    {
        global $wpdb;
        $prefix = $wpdb->prefix;

        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => DB_HOST, // Defined in wp-config.php
            'database'  => DB_NAME, // Defined in wp-config.php
            'username'  => DB_USER, // Defined in wp-config.php
            'password'  => DB_PASSWORD, // Defined in wp-config.php
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => $prefix,
        ]);

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }
}
