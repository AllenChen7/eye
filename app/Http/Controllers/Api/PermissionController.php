<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Model\Role;
use App\Model\RoleHasPermission;
use App\Models\Common;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
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

    /**
     * 权限集列表
     * @return \Illuminate\Http\JsonResponse
     */
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
            $role['updated_at'] = $role['updated_at']->toDateTimeString();
        }

        return $this->successResponse($roles);
    }

    public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:roles',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $role = \Spatie\Permission\Models\Role::findById($request->input('id'));
        $allPermission = Permission::all()->toArray();
        $roleHasPermission = RoleHasPermission::whereRoleId($role['id'])->get()->pluck('permission_id')->toArray();

        foreach ($allPermission as &$permission) {
            if (in_array($permission['id'], $roleHasPermission)) {
                $permission['is_check'] = 1;
            } else {
                $permission['is_check'] = 0;
            }
        }

        $arr = [];
        foreach ($allPermission as $permission) {
            $arr = explode('-', $permission['name']);
            $first = head($arr);

            $arr[$first][] = $permission;
        }

        dda($arr);
    }
}
