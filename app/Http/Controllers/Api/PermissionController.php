<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Model\Role;
use App\Model\RoleHasPermission;
use App\Models\Common;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
//         $role = Role::create([
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initRolesList(Request $request)
    {
        if (auth()->user()->type <= 0) {
            return $this->errorResponse('没有权限', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'type'                  => [
                'required', Rule::in([0, 1]),
            ]
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }
        // 0 角色管理、1 权限集管理
        if ($request->input('type') == 1) {

            if (auth()->user()->type != Common::TYPE_XM) {
                return $this->errorResponse('没有权限', [],403);
            }

            $roles = Role::whereCreateUserId(0)->get();
        } else {
            $roles = Role::whereCreateUserId(auth()->id())->get();
        }

        foreach ($roles as $key => &$role) {
            if ($role['name'] == 'xm') {
                unset($roles[$key]);
                continue;
            }

            $role['count'] = RoleHasPermission::where([
                'role_id' => $role['id']
            ])->count();
            $role['role_name'] = trans('permission.' . $role['name']);
            $role['last_user'] = User::whereId($role['last_user_id'])->first()->name ?? '-';
        }

        return $this->successResponse($roles);
    }

    public function view(Request $request)
    {
        if (auth()->user()->type <= 0) {
            return $this->errorResponse('没有权限', [], 403);
        }

        $permissionArr = Common::permissionArr();
        $userPermission = Common::getPermissionList();
        $validator = Validator::make($request->all(), [
            'id'    => 'nullable|exists:roles',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $allPermission = [];
        $roleHasPermission = [];

        if ($request->input('id')) {
            $role = \Spatie\Permission\Models\Role::findById($request->input('id'));
            $allPermission = Permission::all()->toArray();
            $roleHasPermission = RoleHasPermission::whereRoleId($role['id'])->get()->pluck('permission_id')->toArray();
        }

        $perArr = [];
        $checkArr = [];

        foreach ($allPermission as &$permission) {
            if (in_array($permission['id'], $roleHasPermission)) {
                $permission['is_check'] = 1;
                $perArr[$permission['name']] = 1;
                $checkArr[] = $permission['name'];
            } else {
                $permission['is_check'] = 0;
                $perArr[$permission['name']] = 0;
            }
        }

        foreach ($permissionArr as &$permission) {
            $permission['is_check'] = $perArr[$permission['id']] ?? 0;

            if ($permission['child']) {
                foreach ($permission['child'] as &$per) {
                    $per['is_check'] = $perArr[$per['id']] ?? 0;

                    if ($per['child']) {
                        foreach ($per['child'] as &$p) {
                            $p['is_check'] = $perArr[$p['id']] ?? 0;
                        }
                    }
                }
            }
        }

        return $this->successResponse([
            'permission_arr' => $permissionArr,
            'check_arr' => $checkArr
        ]);
    }

    public function roleList(Request $request)
    {
        $data = Role::where([
            'create_user_id' => auth()->id()
        ])->select(['id', 'name'])->get();

        return $this->successResponse($data);
    }

    /**
     * 添加角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required',
            'permission_arr' => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $roles = \Spatie\Permission\Models\Role::create([
            'name' => auth()->id() . '_' . $request->input('name'),
            'create_user_id' => auth()->id(),
            'last_user_id' => auth()->id()
        ]);

        $res = $roles->givePermissionTo($request->input('permission_arr'));

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    /**
     * 给角色添加权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:roles',
            'permission_arr' => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $role = \Spatie\Permission\Models\Role::findById($request->input('id'));
        $res = $role->givePermissionTo($request->input('permission_arr'));

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
