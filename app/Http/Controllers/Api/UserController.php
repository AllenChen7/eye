<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\ModelsData\UsersData;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function list(Request $request)
    {
        $model = new UsersData();
        $model->name = $request->input('name', '');
        $model->phone = $request->input('phone', '');

        $count = $model->rowCount();
    }
}
