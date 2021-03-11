<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Common;
use Illuminate\Http\Request;

class PermissionController extends ApiController
{
    public function permissionList()
    {
        return $this->successResponse(Common::typeArr());
    }
}
