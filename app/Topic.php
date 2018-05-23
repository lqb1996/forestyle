<?php

namespace App;

class Topic extends Model
{
    protected $table = "topics";

    /*
     * 属于这个主题的所有文章
     */

    public function posts()
    {
        return $this->belongsToMany(\App\Post::class, 'post_topics', 'topic_id', 'post_id')->withPivot(['topic_id', 'post_id']);
    }

    public function parent()
    {
        return $this->hasOne(get_class($this), $this->getKeyName(), 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(get_class($this), 'parent_id', $this->getKeyName());
    }
}
