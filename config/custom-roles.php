<?php

class CustomSLRoles {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'addRoles' ) );
	}

	public static function addRoles(): void {
		self::addClientAdminRole();
		self::addClientEmployeeRole();
		self::addInternalAdminRole();
		self::addInternalEmployeeRole();
	}

	private static function addClientAdminRole(): void {
		add_role( 'client_admin', 'Client Admin');
	}

	private static function addClientEmployeeRole(): void {
		add_role( 'client_employee', 'Client Employee');
	}

	private static function addInternalAdminRole(): void {
		add_role( 'internal_admin', 'Internal Admin');
	}

	private static function addInternalEmployeeRole(): void {
		add_role( 'internal_employee', 'Internal Employee');
	}
}

CustomSLRoles::init();
