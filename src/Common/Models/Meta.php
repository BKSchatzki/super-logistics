<?php

namespace SL\Common\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Common\Traits\Model_Events;
use SL\Milestone\Models\Milestone;

class Meta extends Eloquent {

    use Model_Events;

    protected $table = 'pm_meta';

    protected $fillable = [
        'entity_id',
        'entity_type',
        'meta_key',
        'meta_value',
        'project_id',
        'created_by',
        'updated_by',
    ];

    public function milestone() {
        return $this->belongsTo( 'SL\Milestone\Models\Milestone', 'entity_id' );
    }

    public function getMetaValueAttribute( $value ) {
        return maybe_unserialize( $value );
    }

    public function setMetaValueAttribute( $value ) {
        if( !is_serialized( $value ) ) { 
            $value = maybe_serialize($value); 
        }

        $this->attributes['meta_value'] = $value;
    }
}
