<?php

namespace BigTB\SL\API\Show\Controllers;

use WP_REST_Request;
use WP_Error;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use BigTB\SL\Setup\Controller;
use BigTB\SL\API\Show\Models\Show;
use BigTB\SL\API\Show\Transformers\ShowTransformer;

// TODO: Build out show controller
// TODO: Test this controller
// TODO: Error Handling

class ShowController extends Controller {

	public static function get( WP_REST_Request $request ): array {
		// Fetched with the ENTITY ID as the primary key
		// Yes it's a little convoluted, but this way it's more easily accessible for manipulation from the user model
		$params   = $request->get_params();
		// Some of these are through the relationship, so adjust for that in addWhereClauses
		$query    = self::addWhereClauses( Show::query(), $params, [
			'name',
			'client_id',
			'min_carat_weight',
			'carat_weight_inc',
			'date_start',
			'date_end',
			'floor_plan_path',
			'name',
			'type',
			'phone',
			'email',
			'address',
			'city',
			'state',
			'zip',
			'logo_path',
			'active',
		] );
		$entities = $query->get();
		$resource = new Collection( $entities, new EntityTransformer );

		return self::prepareArrayResponse( $resource );
	}

    public static function create(WP_REST_Request $request): array {

        // Store images first
        $logoFile = $request->get_file_params()['logoFile'] ?? null;
        $floorPlanFile = $request->get_file_params()['floorPlanFile'] ?? null;

        $logo_path = $logoFile ? self::handleImageUpload($logoFile) : '';
        $floor_plan_path = $floorPlanFile ? self::handleImageUpload($floorPlanFile) : '';

        // Use get_param() to retrieve each input field, handle empty/null values as needed
        $show_data = [
            'date_start' => sanitize_text_field($request->get_param('dateStart')),
            'date_end' => sanitize_text_field($request->get_param('dateEnd')) == 'null' ?
                null : sanitize_text_field($request->get_param('dateEnd')),
            'date_expiry' => sanitize_text_field($request->get_param('dateExpiry')) == 'null' ?
                null : sanitize_text_field($request->get_param('dateExpiry')),
            'min_carat_weight' => sanitize_text_field($request->get_param('minCaratWeight')),
            'carat_weight_inc' => sanitize_text_field($request->get_param('caratWeightInc')),
            'client_id' => sanitize_text_field($request->get_param('clientID')),
            'floor_plan_path' => $floor_plan_path,
        ];

        $entity_data = [
            'name' => sanitize_text_field($request->get_param('name')),
            'type' => 5,  // Static value
            'address' => sanitize_text_field($request->get_param('address')),
            'city' => sanitize_text_field($request->get_param('city')),
            'state' => sanitize_text_field($request->get_param('state')),
            'zip' => sanitize_text_field($request->get_param('zip')),
            'phone' => sanitize_text_field($request->get_param('phone')),
            'email' => sanitize_email($request->get_param('email')),
            'logo_path' => $logo_path,
        ];

        $show = Show::create($show_data);
        $entity = $show->entity()->create($entity_data);
        $show->entity_id = $entity->id;
        $show->save();
        foreach (json_decode(stripslashes($_POST["places"]), true) as $data) {
            $show->places()->create($data);
        }

        $resource = new Item($show, new ShowTransformer);

        return self::prepareArrayResponse( $resource );
    }

	public static function update(WP_REST_Request $request): array {
		$show = Show::find($request->get_param('id'));

		// Use get_param() to retrieve each input field, handle empty/null values as needed
		$show->date_start = sanitize_text_field($request->get_param('dateStart'));
		$show->date_end = sanitize_text_field($request->get_param('dateEnd')) == 'null' ?
			null : sanitize_text_field($request->get_param('dateEnd'));
		$show->date_expiry = sanitize_text_field($request->get_param('dateExpiry')) == 'null' ?
			null : sanitize_text_field($request->get_param('dateExpiry'));
		$show->min_carat_weight = sanitize_text_field($request->get_param('minCaratWeight'));
		$show->carat_weight_inc = sanitize_text_field($request->get_param('caratWeightInc'));
		$show->client_id = sanitize_text_field($request->get_param('clientID'));
		$show->floor_plan_path = sanitize_text_field($request->get_param('floorPlanPath'));

		$show->entity->name = sanitize_text_field($request->get_param('name'));
		$show->entity->address = sanitize_text_field($request->get_param('address'));
		$show->entity->city = sanitize_text_field($request->get_param('city'));
		$show->entity->state = sanitize_text_field($request->get_param('state'));
		$show->entity->zip = sanitize_text_field($request->get_param('zip'));
		$show->entity->phone = sanitize_text_field($request->get_param('phone'));
		$show->entity->email = sanitize_email($request->get_param('email'));
		$show->entity->logo_path = sanitize_text_field($request->get_param('logoPath'));

		$show->save();
		$show->entity->save();

		$resource = new Item($show, new ShowTransformer);

		return self::prepareArrayResponse( $resource );
	}

	public static function inactivate(WP_REST_Request $request): array {
		$show = Show::find($request->get_param('id'));
		$show->active = 0;
		$show->save();

		$resource = new Item($show, new ShowTransformer);

		return self::prepareArrayResponse( $resource );
	}

	public static function delete(WP_REST_Request $request): array {
		$show = Show::find($request->get_param('id'));
		$show->delete();

		return self::prepareArrayResponse( [] );
	}

    private static function handleImageUpload($file):string | WP_Error {
        // Define overrides
        $overrides = array(
            'test_form' => false,
            'mimes' => array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif'
            )
        );

        // Handle file upload
        $upload = wp_handle_upload($file, $overrides);

        // Check for errors
        if (isset($upload['error'])) {
            return new WP_Error('upload_failed', $upload['error'], array('status' => 500));
        }

        // File upload successful
        return $upload['file'];
    }
}
