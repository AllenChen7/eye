<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\ModelsData\UsersData;
use Illuminate\Http\Request;

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
        $count = $model->rowCount();
        $rows = $model->rowsData();

        return $this->successResponse([
            'count' => $count,
            'rows' => $rows
        ]);
    }
}
