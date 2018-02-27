<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CircleImg extends Authenticatable
{
    use Notifiable;

    protected $table = "circleImgs";

    public function circle()
    {
        return $this->belongsTo(\App\Circle::class, 'circle_id','id');
    }

}
