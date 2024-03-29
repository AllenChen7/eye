<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Model\Role;
use App\Model\RoleHasPermission;
use App\Models\Common;
use App\Models\ModelHasRole;
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

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);
        $offset = $page <= 1 ? 0 : ($page - 1) * $limit;
        $name = $request->input('name');

        // 0 角色管理、1 权限集管理
        if ($request->input('type') == 1) {

            if (auth()->user()->type != Common::TYPE_XM) {
                return $this->errorResponse('没有权限', [],403);
            }

            $roles = Role::whereCreateUserId(0)->limit($limit)->offset($offset)->get();
        } else {
            $query = Role::whereCreateUserId(auth()->id());

            if ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            }

            $roles = $query->limit($limit)->offset($offset)->get();
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
            $role['status_name'] = Common::statusArr()[$role['status']];
        }

        return $this->successResponse([
            'count' => $roles->count(),
            'rows' => $roles
        ]);
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

//        foreach ($permissionArr as &$permission) {
//            $permission['is_check'] = $perArr[$permission['id']] ?? 0;
//
//            if ($permission['child']) {
//                foreach ($permission['child'] as &$per) {
//                    $per['is_check'] = $perArr[$per['id']] ?? 0;
//
//                    if ($per['child']) {
//                        foreach ($per['child'] as &$p) {
//                            $p['is_check'] = $perArr[$p['id']] ?? 0;
//                        }
//                    }
//                }
//            }
//        }

        $arr = [];

        foreach ($permissionArr as $permission) {

            if (in_array($permission['id'], $userPermission)) {
                $arr[] = $permission;
                continue;
            }

            if ($permission['child']) {
                foreach ($permission['child'] as $per) {

                    if (in_array($per['id'], $userPermission)) {
                        $per['name'] = $permission['name'] . '-' . $per['name'];
                        $arr[] = $per;
                        continue;
                    }

                    if ($per['child']) {
                        foreach ($per['child'] as $p) {
                            if (in_array($p['id'], $userPermission)) {
                                $p['name'] = $permission['name'] . '-' . $p['name'];
                                $arr[] = $p;
                                continue;
                            }
                        }
                    }
                }
            }
        }

        return $this->successResponse([
            'permission_arr' => $arr,
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
            'permission_arr' => 'required|array',
            'status'                  => [
                'nullable', Rule::in([0, 1]),
            ],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $userPermission = Common::getPermissionList();
        $diff = array_diff($request->input('permission_arr'), $userPermission);

        if ($diff) {
            return $this->errorResponse('赋予权限数据不正确');
        }

        $roles = \Spatie\Permission\Models\Role::create([
            'name' => auth()->id() . '_' . $request->input('name'),
            'create_user_id' => auth()->id(),
            'last_user_id' => auth()->id(),
            'status'    => $request->input('status', 1)
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

        $userPermission = Common::getPermissionList();
        $diff = array_diff($request->input('permission_arr'), $userPermission);

        if ($diff) {
            return $this->errorResponse('赋予权限数据不正确');
        }

        $role = \Spatie\Permission\Models\Role::findById($request->input('id'));
        $rr = $role->permissions()->get()->pluck('id');

        $ss = RoleHasPermission::where('role_id', $request->input('id'))->whereIn('permission_id', $rr)->delete();

        $res = $role->givePermissionTo($request->input('permission_arr'));

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    public function updateStatus(Request $request)
    {
        if (auth()->user()->type <= 0) {
            return $this->errorResponse('没有权限', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'status'                  => [
                'required', Rule::in([0, 1]),
            ],
            'id' => 'required|exists:roles'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = Role::where([
            'id' => $request->input('id')
        ])->update([
            'status' => $request->input('status')
        ]);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    public function delete(Request $request)
    {
        if (auth()->user()->type <= 0) {
            return $this->errorResponse('没有权限', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:roles'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $role = Role::whereId($request->input('id'))->first();

        if (in_array($role->name, Common::typeArr(1))) {
            return $this->errorResponse('当前数据不可被删除');
        }

        $rhpe = RoleHasPermission::whereRoleId($request->input('id'))->exists();

        if ($rhpe) {
            return $this->errorResponse('数据已被使用，不可被删除');
        }

        $mhre = ModelHasRole::whereRoleId($request->input('id'))->exists();

        if ($mhre) {
            return $this->errorResponse('数据已被使用，不可被删除');
        }

        $res = Role::where([
            'id' => $request->input('id')
        ])->delete();

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
