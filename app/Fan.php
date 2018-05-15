<?php

namespace forestyle;

use \forestyle\Model;

class Fan extends Model
{
//    protected $table = "fans";

    public function target(){
        return $this->morphMany('forestyle\Relationship','target');
    }
    /*
     * 粉丝用户
     */
    public function fuser()
    {
        return $this->hasOne(\forestyle\User::class, 'id', 'fan_id');
    }

    /*
     * 明星用户
     */
    public function suser()
    {
        return $this->hasOne(\forestyle\User::class, 'id', 'star_id');
    }
}
