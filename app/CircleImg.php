<?php

namespace App;

use \App\Model;

class CircleImg extends Authenticatable
{
    protected $table = "circleImgs";

    public function circle()
    {
        return $this->belongsTo(\App\Circle::class, 'circle_id','id');
    }

}
