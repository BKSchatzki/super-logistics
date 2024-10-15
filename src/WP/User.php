<?php

namespace SL\WP;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'ID';
    protected $timestamp = false;

    public function meta()
    {
        return $this->hasMany('SL\WP\UserMeta', 'user_id');
    }
}
