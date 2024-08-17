<?php

class SL_Create_Table {

    public function __construct() {

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $this->create_sl_entities();
        $this->create_sl_roles();
        $this->create_sl_shows();
        $this->create_sl_show_places();
        $this->create_sl_user_codes();
        $this->create_sl_transactions();
        $this->create_sl_updates();
        $this->create_sl_items();
        $this->create_sl_entity_users();
        $this->create_sl_trailers();
        $this->create_sl_shipments();
        $this->create_role_user_table();
    }

    private function prefix() {
    	global $wpdb;

    	return $wpdb->prefix;
    }

    private function create_sl_entities():void {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_entities';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `type` INT(11) NOT NULL,
            `address` VARCHAR(255) NOT NULL,
            `city` VARCHAR(255) NULL,
            `state` VARCHAR(255) NULL,
            `zip` VARCHAR(255) NULL,
            `phone` VARCHAR(255) NULL,
            `email` VARCHAR(255) NULL,
            `logo_path` VARCHAR(255) NULL,
            PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    public function create_sl_roles():void
    {
        global $wpdb;
        $table_name = $this->prefix() . 'sl_roles';

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `title` VARCHAR(255) NOT NULL,
			  `slug` VARCHAR(255) NOT NULL,
			  `description` text,
			  `status` tinyINT(2) UNSIGNED NOT NULL DEFAULT '1',
			  `created_by` INT(11) UNSIGNED DEFAULT NULL,
			  `updated_by` INT(11) UNSIGNED DEFAULT NULL,
			  `created_at` timestamp NULL DEFAULT NULL,
			  `updated_at` timestamp NULL DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_shows():void {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_shows';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `entity_id` INT(11) UNSIGNED NULL,
            `client_id` INT(11) UNSIGNED NULL,
            `min_carat_weight` INT(11) UNSIGNED NULL,
            `carat_weight_inc` INT(11) UNSIGNED NULL,
            `date_start` DATE NOT NULL,
            `date_end` DATE NULL,
            `date_expiry` DATE NULL,
            `floor_plan_path` VARCHAR(255) NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`entity_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`client_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE CASCADE
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_show_places():void {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_show_places';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `show_id` INT(11) UNSIGNED NOT NULL,
            `type` VARCHAR(255) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`show_id`) REFERENCES {$wpdb->prefix}sl_shows(`id`) ON DELETE CASCADE
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_user_codes():void {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_user_codes';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `entity_id` INT(11) UNSIGNED NOT NULL,
            `show_id` INT(11) UNSIGNED NOT NULL,
            `code` VARCHAR(255) NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`entity_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`show_id`) REFERENCES {$wpdb->prefix}sl_shows(`id`) ON DELETE CASCADE,
            UNIQUE (`entity_id`, `show_id`)
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_transactions():void {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_transactions';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `show_id` INT(11) UNSIGNED NOT NULL,
          `client_id` INT(11) UNSIGNED NOT NULL,
          `carrier_id` INT(11) UNSIGNED NULL,
          `shipper_id` INT(11) UNSIGNED NULL,
          `exhibitor_id` INT(11) UNSIGNED NOT NULL,
          `shipment` VARCHAR(255) NULL,
          `tracking` VARCHAR(255) NULL,
          `place` INT(11) UNSIGNED NULL,
          `billable_weight` INT(11) NULL,
          `pallet_no` INT(11) NULL,
          `receiver` VARCHAR(255) NULL,
          `trailer` INT(11) NULL,
          `freight_type` INT(11) NULL,
          `created_at` TIMESTAMP NULL DEFAULT NULL,
          `updated_at` TIMESTAMP NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`show_id`) REFERENCES {$wpdb->prefix}sl_shows(`id`) ON DELETE RESTRICT,
          FOREIGN KEY (`client_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE RESTRICT,
          FOREIGN KEY (`carrier_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE RESTRICT,
          FOREIGN KEY (`shipper_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE RESTRICT,
          FOREIGN KEY (`exhibitor_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE RESTRICT,
          FOREIGN KEY (`place`) REFERENCES {$wpdb->prefix}sl_show_places(`id`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_updates():void {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_updates';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `transaction_id` INT(11) UNSIGNED NOT NULL,
          `user_id` BIGINT(20) UNSIGNED NOT NULL,
          `type` INT(11) NOT NULL,
          `datetime` DATETIME NOT NULL,
          `image_path` VARCHAR(255) NOT NULL,
          `note` VARCHAR(255) NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`transaction_id`) REFERENCES {$wpdb->prefix}sl_transactions(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_items():void {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_items';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `transaction_id` INT(11) UNSIGNED NOT NULL,
          `type` INT(11) NOT NULL,
          `pcs` INT(11) NOT NULL,
          `bol_count` INT(11) NULL,
          `weight` INT(11) NOT NULL,
          `special` tinyINT(1) NULL,
          `notes` VARCHAR(255) NULL,
          `tracking` VARCHAR(255) NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`transaction_id`) REFERENCES {$wpdb->prefix}sl_transactions(`id`) ON DELETE CASCADE
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_entity_users():void {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_entity_users';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `entity_id` INT(11) UNSIGNED NOT NULL,
          `user_id` BIGINT(20) UNSIGNED NOT NULL,
          FOREIGN KEY (`entity_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_trailers():void {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_trailers';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `trailer` VARCHAR(255) NOT NULL,
          `carrier_id` INT(11) UNSIGNED NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`carrier_id`) REFERENCES {$wpdb->prefix}sl_entities(`id`) ON DELETE CASCADE
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    private function create_sl_shipments():void {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sl_shipments';

        $sql = "CREATE TABLE IF NOT EXISTS  {$table_name} (
          `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `transaction_id` INT(11) UNSIGNED NOT NULL,
          `trailer_id` INT(11) UNSIGNED NOT NULL,
          `total_pcs` INT(11) NOT NULL,
          `note` VARCHAR(255) NOT NULL,
          `pallet_no` INT(11) NOT NULL,
          `date_received` DATETIME NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`transaction_id`) REFERENCES {$wpdb->prefix}sl_transactions(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`trailer_id`) REFERENCES {$wpdb->prefix}sl_trailers(`id`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }

    public function create_role_user_table()
    {
        global $wpdb;
        $table_name = $this->prefix() . 'pm_role_user';

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) UNSIGNED NOT NULL,
			  `role_id` int(11) UNSIGNED NOT NULL,
			  `project_id` int(11) UNSIGNED DEFAULT NULL,
			  `assigned_by` int(11) UNSIGNED NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `project_id` (`project_id`),
			  KEY `role_id` (`role_id`),
			  KEY `user_id` (`user_id`),
			  KEY `assigned_by` (`assigned_by`)
			) DEFAULT CHARSET=utf8";

        dbDelta($sql);
    }
}
