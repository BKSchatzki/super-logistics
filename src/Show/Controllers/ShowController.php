<?php

namespace SL\Show\Controllers;

use WP_REST_Request;
use League\Fractal\Resource\Item as Item;
use League\Fractal\Resource\Collection as Collection;
use SL\Common\Traits\Transformer_Manager;
use SL\Common\Traits\Request_Filter;
use SL\Show\Models\Show;
use SL\Show\Transformers\ShowTransformer;

class ShowController {
    use Transformer_Manager, Request_Filter;

    public function store(WP_REST_Request $request): array {

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

        return $this->get_response( $resource );
    }

    public function showRelevant(): array {
        $format = 'Y-m-d H:i:s';
        $currentDate = date($format);

        $shows = Show::with(['entity', 'places'])
            ->where(function ($query) use ($currentDate) {
                $query->where('date_expiry', '>', $currentDate)
                    ->orWhereNull('date_expiry')
                    ->orWhere('date_expiry', '0000-00-00');
            })
            ->get();

        $resource = new Collection($shows, new ShowTransformer);

        return $this->get_response( $resource );
    }

    public function show(WP_REST_Request $request): array {
        $show = Show::find($request->get_param('id'));
        $resource = new Item($show, new ShowTransformer);

        return $this->get_response( $resource );
    }

    public function showAll(): array {
        $shows = Show::all();
        $resource = new Collection($shows, new ShowTransformer);

        return $this->get_response( $resource );
    }

    private static function handleImageUpload($file):string {
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
