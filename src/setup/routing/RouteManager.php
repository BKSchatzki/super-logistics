<?php

namespace BigTB\SL\Setup\Routing;

class RouteManager {
	public static string $prefix = 'super-logistics';

	public static function declareRoutes( $routeBatches ): void {
		foreach ( $routeBatches as $routeBatch ) {
			self::declareRouteBatch( $routeBatch );
		}
	}

	public static function declareRouteBatch( $batch ): void {
		extract( $batch );
		foreach ( $routes as $route ) {
			register_rest_route( self::$prefix, $base . '/' . $route['path'],
				self::formatMethods( $handlerClass, $route['methods'] )
			);
		}
	}

	public static function logRegisteredRoutes(): void {
		$routes = rest_get_server()->get_routes();
		foreach ( $routes as $route => $details ) {
			foreach ( $details as $detail ) {
				$methods = implode( ', ', $detail['methods'] );
				error_log( "Registered route: " . $route . " with methods: " . $methods );
			}
		}
	}

	private static function formatMethods( $class, $methods ): array {
		foreach ( $methods as &$method ) {
			$method['callback'] = [ $class, $method['callback'] ];
		}
		return $methods;
	}
}