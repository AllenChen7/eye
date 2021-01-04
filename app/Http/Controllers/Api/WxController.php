<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use EasyWeChat\Factory;
use Illuminate\Http\Request;

class WxController extends ApiController
{
    /**
     * 小程序登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function wxMiniLogin(Request $request)
    {
        $config = config('wxmini');
        $app = Factory::miniProgram($config);
        $code = $request->get('code');

        if (!$code) {
            return $this->errorResponse('缺少参数');
        }

        $session = $app->auth->session($code);

        // do something

        return $this->jsonResponse($session);
    }
}
