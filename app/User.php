<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(\App\Post::class, 'user_id', 'id');
    }

    /*
     * 我的粉丝
     */
    public function fans()
    {
//        return $this->hasMany(\App\Fan::class, 'star_id', 'id');
        return $this->morphMany(\App\Relationship::class,'target')->where('target_type', 'App\User');
    }

    /*
     * 当前这个人是否被uid粉了
     */
    public function hasFan($uid)
    {
        return $this->fans()->where('user_id', $uid)->count();
    }

    /*
     * 我粉的人
     */
    public function stars()
    {
//        return $this->hasMany(\App\Fan::class, 'fan_id', 'id');
        return $this->hasMany(\App\Relationship::class, 'user_id', 'id')->where('target_type', 'App\User');
    }

//返回该用户发生的所有关系
    public function relationships()
    {
        return $this->hasMany(\App\Relationship::class,'user_id','id');
    }

    /*
     * 我收到的通知
     */
    public function notices()
    {
        return $this->belongsToMany(\App\Notice::class, 'user_notice', 'user_id', 'notice_id')->withPivot(['user_id', 'notice_id']);
    }

    /*
     * 增加通知
     */
    public function addNotice($notice)
    {
        return $this->notices()->save($notice);
    }

    /*
     * 减少通知
     */
    public function deleteNotice($notice)
    {
        return $this->notices()->detach($notice);
    }

    public function getAvatarAttribute($value)
    {
        if (empty($value)) {
            return '/storage/231c7829cbd325d978898cec389b3f65/egwV7WNPQMSPgMe7WmtjRN7bGKcD0vBAmpRrpLlI.jpeg';
        }
        return $value;
    }
}
