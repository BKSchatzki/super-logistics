<?php

namespace SL\Activity\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\User\Models\User;
use SL\Project\Models\Project;


class Activity extends Eloquent {

    protected $table = 'pm_activities';

    protected $fillable = [
        'actor_id',
        'action',
        'action_type',
        'resource_id',
        'resource_type',
        'meta',
        'project_id'
    ];

    public function actor() {
        return $this->belongsTo( 'SL\User\Models\User', 'actor_id' );
    }

    public function setMetaAttribute( $value ) {
        if ( ! empty($value) ) {
            $this->attributes['meta'] = serialize( $value );
        }
    }

    public function getMetaAttribute( $value ) {
        return unserialize( $value );
    }

    public function project() {
        return $this->belongsTo( 'SL\Project\Models\Project', 'project_id' );
    }

    public function metas() {
        return $this->hasMany( 'SL\Common\Models\Meta', 'entity_type', 'resource_type' );
    }
}
