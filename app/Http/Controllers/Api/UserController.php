<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\ClassData;
use App\Models\Common;
use App\ModelsData\UsersData;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends ApiController
{
    /**
     * 用户列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $model = new UsersData();
        $model->name = $request->input('name', '');
        $model->phone = $request->input('phone', '');
        $model->status = $request->input('status', '');
        $timeArr = $request->input('time_arr', '');
        $model->start_time = $timeArr[0] ?? '';
        $model->end_time = $timeArr[1] ?? '';
        $model->page = $request->input('page', 1);
        $model->limit = $request->input('limit', 20);
        $model->type = $request->input('type', 1);
        $model->is_super = $request->input('is_super', 1);
        $count = $model->rowCount();
        $rows = $model->rowsData();

        return $this->successResponse([
            'count' => $count,
            'rows' => $rows
        ]);
    }

    /**
     * 更新密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                    => 'required|exists:users',
            'password'              => 'required|confirmed|max:50',
            'password_confirmation' => 'required|same:password|max:50'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = User::find($request->input('id'));
        $res->password = Hash::make($request->input('password'));

        if ($res->save()) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    /**
     * 重置密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                    => 'required|exists:users'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = User::find($request->input('id'));
        $res->password = Hash::make(123456);

        if ($res->save()) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    /**
     * 需要修改一下 兼容编辑时所需数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                    => 'required|exists:users'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = User::whereId($request->input('id'))->first();
        $arr = Common::typeArr(1);
        $res['power_list'] = [];

        switch ($res['type']) {
            case Common::TYPE_SCH:
                $prov = User::whereId($res['province_id'])->first();

                if ($prov) {
                    $res['power_list'][] = [
                        'id' => $arr[$prov['type']],
                        'name' => $prov['name']
                    ];
                }

                $city = User::whereId($res['city_id'])->first();

                if ($city) {
                    $res['power_list'][] = [
                        'id' => $arr[$city['type']],
                        'name' => $city['name']
                    ];
                }

                $area = User::whereId($res['area_id'])->first();

                if ($area) {
                    $res['power_list'][] = [
                        'id' => $arr[$area['type']],
                        'name' => $city['name']
                    ];
                }

                break;

            case Common::TYPE_AREA:
                $prov = User::whereId($res['province_id'])->first();

                if ($prov) {
                    $res['power_list'][] = [
                        'id' => $arr[$prov['type']],
                        'name' => $prov['name']
                    ];
                }

                $city = User::whereId($res['city_id'])->first();

                if ($city) {
                    $res['power_list'][] = [
                        'id' => $arr[$city['type']],
                        'name' => $city['name']
                    ];
                }
                break;

            case Common::TYPE_CITY:
                $prov = User::whereId($res['province_id'])->first();

                if ($prov) {
                    $res['power_list'][] = [
                        'id' => $arr[$prov['type']],
                        'name' => $prov['name']
                    ];
                }
                break;
            case Common::TYPE_ZONE:
                $res['power_list'] = $res->getRoleNames();
                break;
        }

        $res['type_name'] = $arr[$res['type']] ?? '普通用户';
        unset($res['class_data_id']);
        unset($res['power_user_id']);
        unset($res['power_type']);

        return $this->successResponse($res);
    }

    /**
     * 用户删除 - 确认下不可删除情况
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                    => 'required|exists:users'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = User::find($request->input('id'));

        if ($res->type == Common::TYPE_XM) {
            return $this->errorResponse('希铭级管理用户不能被删除');
        }

        if ($res->delete()) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|max:64|unique:users',
            'phone'                 => 'required|unique:users|max:32',
            'remark'                => 'nullable|max:200',
            'id'                    => 'required|exists:users',
            'type'                  => [
                'required', Rule::in(Common::typeArrKeys()),
            ]
        ]);
        // 如果是校级的话需要指定省级-市级-县级并创建学校数据

        $validator->sometimes('province_id', 'required|exists:users,id', function ($input) {
            return in_array($input->type, [
                Common::TYPE_CITY, Common::TYPE_SCH, Common::TYPE_AREA
            ]);
        });

        $validator->sometimes('city_id', 'required|exists:users,id', function ($input) {
            return in_array($input->type, [
                Common::TYPE_SCH, Common::TYPE_AREA
            ]);
        });

        $validator->sometimes('area_id', 'required|exists:users,id', function ($input) {
            return in_array($input->type, [
                Common::TYPE_SCH
            ]);
        });

        $validator->sometimes('roles_arr', 'nullable|array', function ($input) {
            return in_array($input->type, [
                Common::TYPE_ZONE
            ]);
        });

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $model              = User::whereId($request->input('id'))->first();
        $model->name        = $request->input('name');
        $model->phone       = $request->input('phone');
        $model->remark      = $request->input('remark');
        $model->type        = $request->input('type');

        switch ($request->input('type')) {
            case Common::TYPE_CITY:
                $model->province_id = $request->input('province_id');
                break;
            case Common::TYPE_AREA:
                $model->province_id = $request->input('province_id');
                $model->city_id     = $request->input('city_id');
                // todo 可以进一步验证
                break;
            case Common::TYPE_SCH:
                $model->province_id = $request->input('province_id');
                $model->city_id     = $request->input('city_id');
                $model->area_id     = $request->input('area_id');

                if (Common::TYPE_SCH) {
                    // 先处理学校数据
                    $classData = ClassData::firstOrCreate([
                        'province_id'   => $request->input('province_id'),
                        'city_id'       => $request->input('city_id'),
                        'area_id'       => $request->input('area_id'),
                        'name'          => $request->input('name')
                    ], [
                        'province_id'   => $request->input('province_id'),
                        'city_id'       => $request->input('city_id'),
                        'area_id'       => $request->input('area_id'),
                        'name'          => $request->input('name'),
                        'create_user_id'=> \auth()->id()
                    ]);

                    $model->class_data_id = $classData->id;
                }
                break;
        }

        if ($model->save()) {

            if ($request->input('type') == Common::TYPE_ZONE) {
                $model->syncRoles($request->input('roles_arr'));
            }

            return $this->successResponse();
        } else {
            return $this->errorResponse();
        }
    }

    /**
     * 更改用户状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'                  => [
                'required', Rule::in([0, 1]),
            ],
            'id' => 'required|exists:users'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $res = User::where([
            'id' => $request->input('id')
        ])->update([
            'status' => $request->input('status')
        ]);

        if ($res) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
