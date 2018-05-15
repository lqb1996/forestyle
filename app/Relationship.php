<?php

namespace forestyle;


class Relationship extends Model
{
    protected $table = "relationships";

    public function user()
    {
        return $this->belongsTo(\forestyle\User::class, 'user_id', 'id');
    }

    public function target()
    {
        return $this->morphTo();
    }
}
