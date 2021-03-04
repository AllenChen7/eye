<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 为了兼容 mysql 5.7 以下
        //Schema::defaultStringLength(191);
        app(\Dingo\Api\Exception\Handler::class)->register(function (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $exception) {
            return \Illuminate\Support\Facades\Response::make([
                'code' => 401,
                'msg'  => 'token 无效，请重新登录',
                'data' => []
            ], 401);
        });

        app(\Dingo\Api\Exception\Handler::class)->register(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $exception) {
            return \Illuminate\Support\Facades\Response::make([
                'code' => 405,
                'msg'  => '请求方式错误',
                'data' => []
            ], 405);
        });
    }
}
