<?php

namespace BigTB\SL\Setup\DB;

class TableManager {

	public static function init(): void {

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		self::createUserStatusTable();
		self::createEntitiesTable();
		self::createShowsTable();
		self::createTransactionsTable();
		self::createShowPlaceTable();
		self::createEntityUsersTable();

		// If users are not already in the active users table, add them
		self::populateUserStatusTable();
	}

	private static function prefix(): string {
		global $wpdb;

		return $wpdb->prefix;
	}

	private static function createUserStatusTable(): void {
		$prefix     = self::prefix();
		$table_name = $prefix . 'sl_user_status';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  `user_id` BIGINT(20) UNSIGNED NOT NULL,
		  `active` TINYINT(1) DEFAULT 1,
		  `trashed` TINYINT(1) DEFAULT 0,
		  PRIMARY KEY (`user_id`),
		  FOREIGN KEY (`user_id`) REFERENCES " . $prefix . "users(`ID`) ON DELETE CASCADE
		) DEFAULT CHARSET=utf8";

		dbDelta( $sql );
	}

	private static function populateUserStatusTable(): void {
		global $wpdb;
		$prefix             = self::prefix();
		$users_table        = $prefix . 'users';
		$active_users_table = $prefix . 'sl_user_status';

		$users = $wpdb->get_results( "SELECT ID FROM $users_table" );

		foreach ( $users as $user ) {
			$user_id = $user->ID;
			$exists  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $active_users_table WHERE user_id = %d", $user_id ) );

			if ( ! $exists ) {
				$wpdb->insert( $active_users_table, [
					'user_id' => $user_id,
					'active'  => 1,
					'trashed' => 0
				] );
			}
		}
	}

	private static function createEntitiesTable(): void {
		// This table is used for Clients, Shows, and Carriers
		// Clients are type 1,
		// Shows are type 2, and
		// Carriers are type 3
		$table_name = self::prefix() . 'sl_entities';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `type` INT(11) NOT NULL,
            `address` VARCHAR(255) NULL,
            `city` VARCHAR(255) NULL,
            `state` VARCHAR(255) NULL,
            `zip` VARCHAR(255) NULL,
            `phone` VARCHAR(255) NULL,
            `email` VARCHAR(255) NULL,
            `logo_path` VARCHAR(255) NULL,
            `active` TINYINT(1) DEFAULT 1,
            `trashed` TINYINT(1) DEFAULT 0,
            PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8";

		dbDelta( $sql );
	}

	private static function createShowsTable(): void {
		$prefix     = self::prefix();
		$table_name = $prefix . 'sl_shows';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `entity_id` INT(11) UNSIGNED NULL,
            `client_id` INT(11) UNSIGNED NULL,
            `min_carat_weight` INT(11) UNSIGNED NULL,
            `carat_weight_inc` INT(11) UNSIGNED NULL,
            `date_start` DATE NOT NULL,
            `date_end` DATE NULL,
            `floor_plan_path` VARCHAR(255) NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`entity_id`) REFERENCES " . $prefix . "sl_entities(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`client_id`) REFERENCES " . $prefix . "sl_entities(`id`) ON DELETE CASCADE
        ) DEFAULT CHARSET=utf8";

		dbDelta( $sql );
	}

	private static function createTransactionsTable(): void {
		$prefix     = self::prefix();
		$table_name = $prefix . 'sl_transactions';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
          `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(255) NOT NULL,
          `show_id` INT(11) UNSIGNED NOT NULL,
          `active` TINYINT(1) DEFAULT 1,
          `trashed` TINYINT(1) DEFAULT 0,
          `created_at` TIMESTAMP NULL DEFAULT NULL,
          `created_by` BIGINT(20) UNSIGNED NOT NULL,
          `updated_at` TIMESTAMP NULL DEFAULT NULL,
          `updated_by` BIGINT(20) UNSIGNED NOT NULL,
          `shipper` varchar(255) NOT NULL,
          `exhibitor` VARCHAR(255) NOT NULL,
          `carrier` VARCHAR(255) NOT NULL,
          `tracking` VARCHAR(255) NOT NULL,
          `zone_id` INT(11) UNSIGNED NOT NULL,
          `booth_id` INT(11) UNSIGNED NOT NULL,
          `crate_pcs` INT(11) UNSIGNED DEFAULT 0,
          `carton_pcs` INT(11) UNSIGNED DEFAULT 0,
          `skid_pcs` INT(11) UNSIGNED DEFAULT 0,
          `fiber_case_pcs` INT(11) UNSIGNED DEFAULT 0,
          `carpet_pcs` INT(11) UNSIGNED DEFAULT 0,
          `misc_pcs` INT(11) UNSIGNED DEFAULT 0,
          `total_pcs` INT(11) UNSIGNED NOT NULL,
          `total_weight` INT(11) UNSIGNED NOT NULL,
          `remarks` TEXT NULL,
          `special_handling` INT(1) UNSIGNED DEFAULT 0,
          `shipper_city` VARCHAR(255) NOT NULL,
          `shipper_state` VARCHAR(255) NOT NULL,
          `shipper_zip` VARCHAR(255) NOT NULL,
          `trailer` VARCHAR(255) NULL,
          `pallet` VARCHAR(255) NULL,
          `image_path` VARCHAR(255) NULL,
          `freight_type` VARCHAR(255) NOT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`created_by`) REFERENCES " . $prefix . "users(`ID`) ON DELETE RESTRICT,
          FOREIGN KEY (`updated_by`) REFERENCES " . $prefix . "users(`ID`) ON DELETE RESTRICT,
          FOREIGN KEY (`show_id`) REFERENCES " . $prefix . "sl_entities(`id`) ON DELETE RESTRICT,
          FOREIGN KEY (`zone_id`) REFERENCES " . $prefix . "sl_show_places(`id`) ON DELETE RESTRICT,
          FOREIGN KEY (`booth_id`) REFERENCES " . $prefix . "sl_show_places(`id`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

		dbDelta( $sql );
	}

	private static function createShowPlaceTable(): void {
		$prefix     = self::prefix();
		$table_name = $prefix . 'sl_show_places';

		// zones are type 1
		// booths are type 2
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		  `type` VARCHAR(255) NOT NULL,
		  `show_id` INT(11) UNSIGNED NOT NULL,
		  `name` VARCHAR(255) NOT NULL,
		  `trashed` TINYINT(1) DEFAULT 0,
		  PRIMARY KEY (`id`),
		  FOREIGN KEY (`show_id`) REFERENCES " . $prefix . "sl_entities(`id`) ON DELETE CASCADE
		) DEFAULT CHARSET=utf8";

		dbDelta( $sql );
	}

	private static function createEntityUsersTable(): void {
		$prefix     = self::prefix();
		$table_name = self::prefix() . 'sl_entity_users';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
          `entity_id` INT(11) UNSIGNED NOT NULL,
          `user_id` BIGINT(20) UNSIGNED NOT NULL,
          FOREIGN KEY (`entity_id`) REFERENCES " . $prefix . "sl_entities(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`user_id`) REFERENCES " . $prefix . "users(`ID`) ON DELETE CASCADE
        ) DEFAULT CHARSET=utf8";

		dbDelta( $sql );
	}
}
