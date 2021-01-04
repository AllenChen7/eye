<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * 返回 json
     * @param $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponse($data, $status = 200)
    {
        return response()->json($data, $status);
    }

    /**
     * 成功数据
     * @param $data
     * @param string $msg
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data = [], $msg = '处理成功', $code = 200)
    {
        $data = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];

        return $this->jsonResponse($data);
    }

    /**
     * 失败数据
     * @param $data
     * @param string $msg
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($msg = '处理失败', $data = [] , $code = 400)
    {
        $data = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];

        return $this->jsonResponse($data);
    }
}
