<?php

namespace BigTB\SL\Setup\Routing;

class Permissions {
	public static function isLoggedIn(): bool {
		return is_user_logged_in();
	}

	public static function isWPAdmin(): bool {
		return current_user_can('administrator');
	}

	public static function isAdmin(): bool {
		$isAppAdmin = current_user_can('client_admin') || current_user_can('internal_admin');
		return $isAppAdmin || self::isWPAdmin();
	}

	public static function isInternalAdmin(): bool {
		return current_user_can('internal_admin') || self::isWPAdmin();
	}

	public static function isClientAdmin(): bool {
		return current_user_can('client_admin') || self::isWPAdmin();
	}

	public static function isClientEmployee(): bool {
		return current_user_can('client_employee') || self::isWPAdmin();
	}

	public static function isInternal(): bool {
		$isInternal = current_user_can('internal_admin') || current_user_can('internal_employee');
		return $isInternal || self::isWPAdmin();
	}
}