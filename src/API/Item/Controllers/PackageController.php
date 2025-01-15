<?php

namespace BigTB\SL\API\Package\Controllers;

use WP_REST_Request;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use BigTB\SL\Setup\WP\ResponseManager;
use BigTB\SL\API\Package\Models\Package;
use BigTB\SL\API\Package\Transformers\PackageTransformer;

class PackageController {

	use ResponseManager;

	public static function get(WP_REST_Request $request): array {
		$packages = Package::all();
		$resource = new Collection($packages, new PackageTransformer);

		return self::prepareArrayResponse($resource);
	}

	public static function create(WP_REST_Request $request ) {
		$package_data = [
			'item_id' => sanitize_text_field($_POST['item_id']),
			'carrier_id' => sanitize_text_field($_POST['carrier_id']),
			'tracking_number' => sanitize_text_field($_POST['tracking_number']),
			'weight' => sanitize_text_field($_POST['weight']),
			'cost' => sanitize_text_field($_POST['cost']),
			'ship_date' => sanitize_text_field($_POST['ship_date']),
			'delivery_date' => sanitize_text_field($_POST['delivery_date']),
			'status' => sanitize_text_field($_POST['status']),
			'notes' => sanitize_text_field($_POST['notes'])
		];

		$package = Package::create($package_data);

		$resource = new Item($package, new PackageTransformer);

		return self::prepareArrayResponse($resource);
	}

	public static function update(WP_REST_Request $request): array {
		$package = Package::find($request->get_param('id'));

		if (!$package) {
			return self::prepareErrorResponse('Package not found', 404);
		}

		$package->item_id = sanitize_text_field($request->get_param('item_id'));
		$package->carrier_id = sanitize_text_field($request->get_param('carrier_id'));
		$package->tracking_number = sanitize_text_field($request->get_param('tracking_number'));
		$package->weight = sanitize_text_field($request->get_param('weight'));
		$package->cost = sanitize_text_field($request->get_param('cost'));
		$package->ship_date = sanitize_text_field($request->get_param('ship_date'));
		$package->delivery_date = sanitize_text_field($request->get_param('delivery_date'));
		$package->status = sanitize_text_field($request->get_param('status'));
		$package->notes = sanitize_text_field($request->get_param('notes'));

		$package->save();

		$resource = new Item($package, new PackageTransformer);

		return self::prepareArrayResponse($resource);
	}

	public static function delete(WP_REST_Request $request): array {
		$package = Package::find($request->get_param('id'));

		if (!$package) {
			return self::prepareErrorResponse('Package not found', 404);
		}

		$package->delete();

		return self::prepareArrayResponse([]);
	}

	public static function getLabels(WP_REST_Request $request): array {
		$package = Package::find($request->get_param('id'));

		if (!$package) {
			return self::prepareErrorResponse('Package not found', 404);
		}

		$labels = $package->getLabels();

		return self::prepareArrayResponse($labels);
	}
}