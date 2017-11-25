<?php

namespace App;

use \App\Model;

class Comment extends Model
{
    protected $table = "comments";

    //多态关联
    public function commentable()
    {
        return $this->morphTo();
    }

    //自关联
    public function parent()
    {
        return $this->hasOne(get_class($this), $this->getKeyName(), 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(get_class($this), 'parent_id', $this->getKeyName());
    }

    public function user()
    {
        return $this->belongsTo('\App\User', 'user_id', 'id');
    }
}
