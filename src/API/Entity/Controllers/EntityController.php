<?php


namespace BigTB\SL\API\Entity\Controllers;

use WP_REST_Request;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use BigTB\SL\Setup\ResponseManager;
use BigTB\SL\API\Entity\Models\Entity;
use BigTB\SL\API\Entity\Transformers\EntityTransformer;

class EntityController
{
    use ResponseManager;

    public function show(WP_REST_Request $request):array
    {
        // Fetching all entities, then transform and return
        $type = ($request->get_param('type'));
        $entities = Entity::where('type', $type)->get();
        $resource = new Collection($entities, new EntityTransformer);

        return $this->prepareArrayResponse($resource);
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

        return $this->prepareArrayResponse($resource);
    }

    public function updateCode(WP_REST_Request $request): bool {
        $entity_id = $request->get_param('entity_id');
        $show_id = $request->get_param('show_id');
        $code = $request->get_param('code');

        $entity = Entity::find($entity_id);
        $entity->codes()->syncWithoutDetaching([$show_id => ['code' => $code]]);

        return true;
    }

    public function getCodes(WP_REST_Request $request): array
    {
        // Fetch all entities with their related codes
        $entities = Entity::with('codes.entity')->get();

        // Transform and return the entities and their related codes
        $resource = new Collection($entities, new EntityTransformer);

        return $this->prepareArrayResponse($resource);
    }

    public function registerUser(): bool {
        $code = $_POST['code'];

        $entity = Entity::whereHas('codes', function ($query) use ($code) {
            $query->where('code', $code);
        })->first();

        if (!$entity) {
            return false; // Or handle the error as needed
        }

        $currentUser = wp_get_current_user();
        if (!$currentUser || $currentUser->ID == 0) {
            return false; // Or handle the error as needed
        }

        $entity->users()->attach($currentUser->ID);

        return true;
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
