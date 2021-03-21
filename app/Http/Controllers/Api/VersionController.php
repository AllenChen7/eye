<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\XmVersion;
use Illuminate\Http\Request;

class VersionController extends ApiController
{
    public function checkUpdate(Request $request)
    {
        $vCode = $request->input('v_code', '');

        if (!$vCode) {
            return $this->errorResponse('暂无更新版本');
        }

        $res = XmVersion::where('v_code', '>', $vCode)->select([
            'v_code', 'url'
        ])->first();

        if ($res) {
            return $this->successResponse($res);
        }

        return $this->errorResponse('暂无更新版本');
    }
}
