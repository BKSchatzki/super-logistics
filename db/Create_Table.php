<?php

class SL_Create_Table {

    public function __construct() {

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $this->create_sl_entities();
        $this->create_sl_roles();
        $this->create_sl_shows();
        $this->create_sl_transactions();
        $this->create_sl_updates();
        $this->create_sl_notes();
        $this->create_sl_items();
        $this->create_sl_exhibitor_users();
        $this->create_sl_trailers();
        $this->create_sl_shipments();
    }

    private function prefix() {
    	global $wpdb;

    	return $wpdb->prefix;
    }

    private function create_sl_entities() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_entities';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `logo_path` varchar(255) NULL,
          `type` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    public function create_sl_roles()
    {
        global $wpdb;
        $table_name = $this->prefix() . 'sl_roles';

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) NOT NULL,
			  `slug` varchar(255) NOT NULL,
			  `description` text,
			  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
			  `created_by` int(11) UNSIGNED DEFAULT NULL,
			  `updated_by` int(11) UNSIGNED DEFAULT NULL,
			  `created_at` timestamp NULL DEFAULT NULL,
			  `updated_at` timestamp NULL DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_shows() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_shows';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `logo_path` varchar(255) NULL,
          `date` date NOT NULL,
          `address` varchar(255) NOT NULL,
          `city` varchar(255) NULL,
          `state` varchar(255) NULL,
          `zip` varchar(255) NULL,
          `floor_plan_path` varchar(255) NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_transactions() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_transactions';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `exhibitor_id` int(11) UNSIGNED NOT NULL,
          `carrier_id` int(11) UNSIGNED NOT NULL,
          `show_id` int(11) UNSIGNED NOT NULL,
          `zone` varchar(255) NOT NULL,
          `color` varchar(255) NOT NULL,
          `billable_weight` int(11) NOT NULL,
          `city` varchar(255) NOT NULL,
          `state` varchar(255) NOT NULL,
          `zip` varchar(255) NOT NULL,
          `pallet_no` int(11) NOT NULL,
          `freight_type` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`exhibitor_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE RESTRICT,
          FOREIGN KEY (`carrier_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE RESTRICT,
          FOREIGN KEY (`show_id`) REFERENCES {$wpdb->prefix}sl_shows(`id`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_updates() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_updates';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `transaction_id` int(11) UNSIGNED NOT NULL,
          `user_id` int(11) UNSIGNED NOT NULL,
          `type` int(11) NOT NULL,
          `datetime` datetime NOT NULL,
          `image_path` varchar(255) NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`transaction_id`) REFERENCES {$wpdb->prefix}sl_transactions(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`) ON DELETE SET NULL
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_notes() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_notes';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` int(11) UNSIGNED NOT NULL,
          `transaction_id` int(11) UNSIGNED NOT NULL,
          `note` varchar(255) NOT NULL,
          `datetime` datetime NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`) ON DELETE SET NULL,
          FOREIGN KEY (`transaction_id`) REFERENCES {$wpdb->prefix}sl_transactions(`id`) ON DELETE CASCADE
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_items';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `transaction_id` int(11) UNSIGNED NOT NULL,
          `type` int(11) NOT NULL,
          `pcs` int(11) NOT NULL,
          `bol_count` int(11) NOT NULL,
          `weight` int(11) NOT NULL,
          `special` tinyint(1) NOT NULL,
          `notes` varchar(255) NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`transaction_id`) REFERENCES {$wpdb->prefix}sl_transactions(`id`) ON DELETE CASCADE
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_exhibitor_users() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_exhibitor_users';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `exhibitor_id` int(11) UNSIGNED NOT NULL,
          `user_id` int(11) UNSIGNED NOT NULL,
          FOREIGN KEY (`exhibitor_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_trailers() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_trailers';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `trailer` varchar(255) NOT NULL,
          `carrier_id` int(11) UNSIGNED NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`carrier_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE CASCADE
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_shipments() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_shipments';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `transaction_id` int(11) UNSIGNED NOT NULL,
          `trailer_id` int(11) UNSIGNED NOT NULL,
          `total_pcs` int(11) NOT NULL,
          `note` varchar(255) NOT NULL,
          `pallet_no` int(11) NOT NULL,
          `date_received` datetime NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`transaction_id`) REFERENCES {$wpdb->prefix}sl_transactions(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`trailer_id`) REFERENCES {$wpdb->prefix}sl_trailers(`id`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }
}
