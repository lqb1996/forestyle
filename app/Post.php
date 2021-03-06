<?php

namespace App;

use \App\Model;
//use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
//    use Searchable;

    protected $table = "posts";

    /*
     * 搜索的type
     */
//    public function searchableAs()
//    {
//        return 'posts_index';
//    }
//
//    public function toSearchableArray()
//    {
//        return [
//            'title' => $this->title,
//            'content' => $this->content,
//        ];
//    }


    /*
     * 所有评论
     */
    public function comments()
    {
        return $this->morphMany(\App\Comment::class,'commentable')->orderBy('created_at', 'desc');
    }

    public function commentable()
    {
        return $this->morphMany(\App\Comment::class,'commentable')->orderBy('created_at', 'desc');
    }

    /*
     * 作者
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    /*
     * 点赞
     */
//    public function zans()
//    {
//        return $this->hasMany(\App\Zan::class)->orderBy('created_at', 'desc');
//    }

    /*
     * 判断一个用户是否已经给这篇文章点赞了
     */
//    public function zan($user_id)
//    {
//        return $this->hasOne(\App\Zan::class)->where('user_id', $user_id);
//    }

    /*
     * 一篇文章有哪些主题
     */
    public function topics()
    {
        return $this->belongsToMany(\App\Topic::class, 'post_topics', 'post_id', 'topic_id')->withPivot(['topic_id', 'post_id']);
    }

    public function postTopics()
    {
        return $this->hasMany(\App\PostTopic::class, 'post_id');
    }

    public function target($user_id)
    {
        return $this->morphMany(\App\Relationship::class,'target')->where('user_id', $user_id);
    }

    public function targets()
    {
        return $this->morphMany(\App\Relationship::class,'target');
    }

    public function grantTopic($topic)
    {
        return $this->topics()->save($topic);
    }

    public function deleteTopic($topic)
    {
        return $this->topics()->detach($topic);
    }

    public function scopeTopicNotBy(Builder $query, $topic_id)
    {
        return $query->doesntHave('postTopics', 'and', function($q) use ($topic_id) {
            $q->where("topic_id", $topic_id);
        });
    }


    public function scopeAuthorBy($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /*
     * 可以显示的文章
     */
    public function scopeAviable($query)
    {
        return $query->whereIn('status', [0, 1]);
    }

}
