<?php

namespace SL\Entity\Models;

use SL\Core\DB_Connection\Model as Eloquent;

class Entity extends Eloquent {
    protected $table = 'sl_entities';
    protected $fillable = [
        'name',
        'type',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'logo_path'
    ];
    public $timestamps = false;

    private static string $transModel = 'SL\Transaction\Models\Transaction';

    public function clientTrans():object {
        return $this->hasMany(self::$transModel, 'client_id');
    }

    public function carrierTrans():object {
        return $this->hasMany(self::$transModel, 'carrier_id');
    }

    public function shipperTrans():object {
        return $this->hasMany(self::$transModel, 'shipper_id');
    }

    public function exhibitorTrans():object {
        return $this->hasMany(self::$transModel, 'shipper_id');
    }

    public function users():object {
        return $this->belongsToMany('SL\User\Models\User', 'sl_entity_users', 'entity_id', 'user_id');
    }

    public function show():object {
        return $this->hasOne('SL\Show\Models\Show', 'entity_id');
    }

    public function codes():object {
        return $this->belongsToMany('SL\Show\Models\Show', 'sl_user_codes', 'entity_id', 'show_id')
                    ->withPivot('code')
                    ->withTimestamps();
    }
}
