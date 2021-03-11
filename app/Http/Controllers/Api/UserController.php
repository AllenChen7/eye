<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Common;
use App\ModelsData\UsersData;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $model->start_time = $request->input('start_time', '');
        $model->end_time = $request->input('end_time', '');
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

        $res = User::whereId($request->input('id'))->first()->toArray();
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
        }

        $res['type'] = $arr[$res['type']];
        unset($res['class_data_id']);
        unset($res['city_id']);
        unset($res['province_id']);
        unset($res['area_id']);
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
        $res->is_del =Common::YES;

        if ($res->save()) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
