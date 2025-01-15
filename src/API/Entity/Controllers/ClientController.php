<?php

namespace BigTB\SL\API\Entity\Controllers;

class ClientController extends EntityController {
	static int $entityType = 1;

	private static function setType( \WP_REST_Request $request): void {
		$request->set_param('type', self::$entityType);
	}

	public static function get( \WP_REST_Request $request): array {
		self::setType($request);
		return parent::get($request);
	}

	public static function create( \WP_REST_Request $request): array {
		self::setType($request);
		return parent::create($request);
	}
}