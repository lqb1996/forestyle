<?php

namespace forestyle;

class AdminPermission extends Model
{
    /*
     * 权限属于哪些角色
     */
    public function roles()
    {
        return $this->belongsToMany(\forestyle\AdminRole::class, 'admin_permission_role', 'permission_id', 'role_id')->withPivot(['permission_id', 'role_id']);
    }
}
