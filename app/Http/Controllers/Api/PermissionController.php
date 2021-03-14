<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Model\Role;
use App\Model\RoleHasPermission;
use App\Models\Common;
use App\User;
use Illuminate\Http\Request;
use function Symfony\Component\String\s;

class PermissionController extends ApiController
{
    /**
     * 获取权限等级
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissionList()
    {
        $type = Common::typeArr();
        $res = [];

        foreach ($type as $key => $value) {
            $res[] = [
                'id' => $key,
                'name' => $value
            ];
        }

        return $this->successResponse($res);
    }

    public function test()
    {
//        $role = Role::create([
//            'name' => 'writer'
//        ]);
//        $permission = Permission::create([
//            'name' => 'edit articles'
//        ]);
        $user = User::whereId(1)->first();
//        $res = $user->givePermissionTo('edit articles');
//        $user->assignRole('writer');
        $res = $user->getRoleNames();
        ddax($res);
    }

    public function initRolesList()
    {
        if (auth()->user()->type !== 10) {
            return $this->errorResponse('没有权限', [], 403);
        }

        $roles = Role::whereCreateUserId(0)->get();

        foreach ($roles as &$role) {
            $role['count'] = RoleHasPermission::where([
                'role_id' => $role['id']
            ])->count();
            $role['role_name'] = trans('permission.' . $role['name']);
            $role['last_user'] = User::whereId($role['last_user_id'])->first()->name ?? '-';
        }

        return $this->successResponse($roles);
    }
}
