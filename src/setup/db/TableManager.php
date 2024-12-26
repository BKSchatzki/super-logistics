<?php

namespace BigTB\SL\Setup\DB;

class TableManager {

	public static function init(): void {

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		self::createEntitiesTable();
		self::createShowsTable();
		self::createTransactionsTable();
		self::createUpdatesTable();
		self::createItemsTable();
		self::createEntityUsersTable();
	}

	private static function prefix(): string {
		global $wpdb;

		return $wpdb->prefix;
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
          `zone` VARCHAR(255) NOT NULL,
          `active` TINYINT(1) DEFAULT 1,
          `created_at` TIMESTAMP NULL DEFAULT NULL,
          `updated_at` TIMESTAMP NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`show_id`) REFERENCES " . $prefix . "sl_shows(`id`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

		dbDelta( $sql );
	}

	private static function createUpdatesTable(): void {
		$prefix     = self::prefix();
		$table_name = self::prefix() . 'sl_updates';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
          `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `transaction_id` INT(11) UNSIGNED NOT NULL,
          `user_id` BIGINT(20) UNSIGNED NOT NULL,
          `type` INT(11) NOT NULL,
          `datetime` DATETIME NOT NULL,
          `image_path` VARCHAR(255) NOT NULL,
          `note` VARCHAR(255) NULL,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`transaction_id`) REFERENCES " . $prefix . "sl_transactions(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`user_id`) REFERENCES " . $prefix . "users(`ID`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

		dbDelta( $sql );
	}

	private static function createItemsTable(): void {
		$prefix     = self::prefix();
		$table_name = self::prefix() . 'sl_items';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
          `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `transaction_id` INT(11) UNSIGNED NOT NULL,
          `type` INT(11) NOT NULL,
          `pcs` INT(11) NOT NULL,
          `bol_count` INT(11) NULL,
          `weight` INT(11) NOT NULL,
          `special` tinyINT(1) DEFAULT 0,
          `notes` VARCHAR(255) NULL,
          `tracking` VARCHAR(255) NULL,
          `carrier` INT(11) UNSIGNED NULL,
          `exhibitor` VARCHAR(255) NOT NULL,
          `returning` tinyINT(1) DEFAULT 0,
          `active` tinyINT(1) DEFAULT 1,
          PRIMARY KEY (`id`),
          FOREIGN KEY (`transaction_id`) REFERENCES " . $prefix . "sl_transactions(`id`) ON DELETE CASCADE,
          FOREIGN KEY (`carrier`) REFERENCES " . $prefix . "sl_entities(`id`) ON DELETE CASCADE
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
          FOREIGN KEY (`user_id`) REFERENCES " . $prefix . "users(`ID`) ON DELETE RESTRICT
        ) DEFAULT CHARSET=utf8";

		dbDelta( $sql );
	}
}
