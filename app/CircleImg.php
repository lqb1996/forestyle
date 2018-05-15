<?php

namespace forestyle;

use \forestyle\Model;

class CircleImg extends Model
{
    protected $table = "circleImgs";

    public function circle()
    {
        return $this->belongsTo(\forestyle\Circle::class, 'circle_id','id');
    }

}
