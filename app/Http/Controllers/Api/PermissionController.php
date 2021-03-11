<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Common;
use Illuminate\Http\Request;

class PermissionController extends ApiController
{
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
}
