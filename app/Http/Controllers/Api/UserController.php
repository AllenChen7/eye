<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
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
}
