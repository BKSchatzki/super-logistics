<?php


namespace SL\Entity\Controllers;

use WP_REST_Request;
use League\Fractal\Resource\Item as Item;
use League\Fractal\Resource\Collection as Collection;
use SL\Common\Traits\Transformer_Manager;
use SL\Common\Traits\Request_Filter;
use SL\Entity\Models\Entity;
use SL\Entity\Transformers\EntityTransformer;

class EntityController
{
    use Transformer_Manager, Request_Filter;

    public function show(WP_REST_Request $request):array
    {
        // Fetching all entities, then transform and return
        $type = ($request->get_param('type'));
        $entities = Entity::where('type', $type)->get();
        $resource = new Collection($entities, new EntityTransformer);

        return $this->get_response($resource);
    }

    public function store(): array
    {// This function is formData in order to handle the image file upload
        $logo_path = isset($_FILES['logoFile']) ? self::handleImageUpload($_FILES['logoFile']) : '';

        $entity_data = [
            'name' => sanitize_text_field($_POST['name']),
            'type' => sanitize_text_field($_POST['type']),
            'address' => sanitize_text_field($_POST['address']),
            'city' => sanitize_text_field($_POST['city']),
            'state' => sanitize_text_field($_POST['state']),
            'zip' => sanitize_text_field($_POST['zip']),
            'phone' => sanitize_text_field($_POST['phone']),
            'email' => sanitize_email($_POST['email']),
            'logo_path' => $logo_path
        ];

        $entity = Entity::create($entity_data);

        $resource = new Item($entity, new EntityTransformer);

        return $this->get_response($resource);
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
