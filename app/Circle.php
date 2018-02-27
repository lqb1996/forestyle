<?php

namespace App;

use \App\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Builder;

class Circle extends Model
{
    use Searchable;

    protected $table = "circles";

    /*
     * 搜索的type
     */
    public function searchableAs()
    {
        return 'circles_index';
    }

    public function toSearchableArray()
    {
        return [
            'title' => $this->user(),
            'content' => $this->content,
        ];
    }

    public function circleImgs()
    {
        return $this->hasMany(\App\CircleImg::class, 'circle_id','id');
    }
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
    public function zans()
    {
        return $this->hasMany(\App\Zan::class)->orderBy('created_at', 'desc');
    }

    public function target($user_id)
    {
        return $this->morphMany(\App\Relationship::class,'target')->where('user_id', $user_id);
    }

    public function targets()
    {
        return $this->morphMany(\App\Relationship::class,'target');
    }


    public function scopeAuthorBy($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /*
     * 可以显示的圈子
     */
    public function scopeAviable($query)
    {
        return $query->whereIn('status', [0, 1]);
    }

}
