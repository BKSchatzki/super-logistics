<?php

namespace BigTB\SL\Setup;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;

trait ResponseManager
{
    public function prepareArrayResponse( $resource, $extra = [] ): array {
        return self::prepareResponse( $resource, $extra );
    }

    public function prepareJSONResponse( $resource, $extra = [] ): string {
        return json_encode( self::prepareResponse($resource, $extra) );
    }

    public function setCreatedBy( $resource ): object {
        $user = wp_get_current_user();
        $resource->created_by = $user->ID;
        $resource->updated_by = $user->ID;
        return $resource;
    }

    public function setUpdatedBy( $resource ): object {
        $user = wp_get_current_user();
        $resource->updated_by = $user->ID;
        return $resource;
    }

    private function prepareResponse( $resource, $extra = [] ): array {
        $manager = new Manager();
        $manager->setSerializer( new DataArraySerializer() );

        if ( isset( $_GET['with'] ) ) {
            $manager->parseIncludes( sanitize_text_field( wp_unslash( $_GET['with'] ) ) ) ;
        }

        if ($resource) {
            $response = $manager->createData( $resource )->toArray();

        } else {
            $response = [];
        }

        return array_merge( $extra, $response );
    }
}