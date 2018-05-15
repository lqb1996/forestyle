<?php

namespace forestyle\Admin\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /*
     * 用户列表
     */
    public function index()
    {
        $users = \forestyle\AdminUser::paginate(10);
        return view('/admin/user/index', compact('users'));
    }

    /*
     * 创建用户
     */
    public function create()
    {
        return view('/admin/user/add');
    }

    /*
     * 创建用户
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:3',
            'password' => 'required'
        ]);

        $name = request('name');
        $password = bcrypt(request('password'));
        \forestyle\AdminUser::create(compact('name', 'password'));
        return redirect('/admin/users');
    }

    /*
     * 角色的权限
     */
    public function role(\forestyle\AdminUser $user)
    {
        $roles = \forestyle\AdminRole::all();
        $myRoles = $user->roles;
        return view('/admin/user/role', compact('roles', 'myRoles', 'user'));
    }

    /*
     * 保存权限
     */
    public function storeRole(\forestyle\AdminUser $user)
    {
        $this->validate(request(),[
            'roles' => 'required|array'
        ]);

        $roles = \forestyle\AdminRole::find(request('roles'));
        $myRoles = $user->roles;

        // 对已经有的权限
        $addRoles = $roles->diff($myRoles);
        foreach ($addRoles as $role) {
            $user->roles()->save($role);
        }

        $deleteRoles = $myRoles->diff($roles);
        foreach ($deleteRoles as $role) {
            $user->deleteRole($role);
        }
        return back();
    }
}
