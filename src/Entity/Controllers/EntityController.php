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

    public function showAllClients():array
    {
        // Fetching all entities, then transform and return
        $entities = Entity::where('type', 1)->get();
        $resource = new Collection($entities, new EntityTransformer);

        return $this->get_response($resource);
    }

    public function showAllCarriers():array
    {
        // Fetching all entities, then transform and return
        $entities = Entity::where('type', 2)->get();
        $resource = new Collection($entities, new EntityTransformer);

        return $this->get_response($resource);
    }

    public function showAllExhibitors():array
    {
        // Fetching all entities, then transform and return
        $entities = Entity::where('type', 3)->get();
        $resource = new Collection($entities, new EntityTransformer);

        return $this->get_response($resource);
    }

    public function showAllShippers():array
    {
        // Fetching all entities, then transform and return
        $entities = Entity::where('type', 4)->get();
        $resource = new Collection($entities, new EntityTransformer);

        return $this->get_response($resource);
    }

    public function store(WP_REST_Request $request): array
    {
        // Handle image upload first
        error_log('$_FILES: ' . print_r($_FILES, true));
        error_log('$request: ' . print_r($request, true));

        $logo_path = $_FILES['logoFile'] ? self::handleImageUpload($_FILES['logoFile']) : null;

        // Proceed with the rest of the entity data
        $entity_data = [
            'name' => sanitize_text_field($request->get_param('name')),
            'type' => sanitize_text_field($request->get_param('type')),
            'address' => sanitize_text_field($request->get_param('address')),
            'city' => sanitize_text_field($request->get_param('city')),
            'state' => sanitize_text_field($request->get_param('state')),
            'zip' => sanitize_text_field($request->get_param('zip')),
            'phone' => sanitize_text_field($request->get_param('phone')),
            'email' => sanitize_email($request->get_param('email')),
            'logo_path' => $logo_path
        ];

        // Create a new entity
        $entity = Entity::create($entity_data);

        // Transforming database model instance
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
