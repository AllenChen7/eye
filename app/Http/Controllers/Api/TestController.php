<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestController extends ApiController
{
    /**
     * 测试非登录接口
     * @return \Illuminate\Http\JsonResponse
     */
    public function test()
    {
        $uri = 'https://v1.hitokoto.cn';
        Log::info('test');
        $cacheStr = 'yy_str';
        $str = Cache::get($cacheStr);

        if ($str) {

            return $this->successResponse([
                'yy' => $str
            ]);
        } else {
            $response = Http::get($uri);

            if ($response->ok()) {
                $str = isset($response->json()['hitokoto']) ? $response->json()['hitokoto'] : '';
                Cache::put($cacheStr, $str, 20);

                return $this->successResponse([
                    'yy' => $str
                ]);
            } else {
                return $this->successResponse([
                    'yy' => '好好学习，天天向上'
                ]);
            }
        }
    }

    public function authTest()
    {
        $uri = 'https://v1.hitokoto.cn';
        $cacheStr = 'yy_str';
        $str = Cache::get($cacheStr);

        if ($str) {

            return $this->successResponse([
                'yy' => $str
            ]);
        } else {
            $response = Http::get($uri);

            if ($response->ok()) {
                $str = isset($response->json()['hitokoto']) ? $response->json()['hitokoto'] : '';
                Cache::put($cacheStr, $str, 20);

                return $this->successResponse([
                    'yy' => $str
                ]);
            } else {
                return $this->successResponse([
                    'yy' => '好好学习，天天向上'
                ]);
            }
        }
    }
}
