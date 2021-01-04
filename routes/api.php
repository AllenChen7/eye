<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    // 无需登录接口组
    $api->group(['namespace' => 'App\Http\Controllers\Api'], function ($api) {
        // 测试接口
        $api->get('test', 'TestController@test');
        $api->get('wx/mini-login', 'WxController@wxMiniLogin');
        // 通过手机号进行登录
        $api->post('auth/login-by-phone', 'AuthController@loginByPhone');
        // 通过邮箱进行登录
        $api->post('auth/login-by-email', 'AuthController@loginByEmail');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
        'prefix'     => 'auth'
    ], function ($api) {
        $api->post('logout', 'AuthController@logout');
        // 用户重置 token
        $api->get('refresh', 'AuthController@refresh');
        // 获取用户信息
        $api->get('me', 'AuthController@me');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
    ], function ($api) {
        $api->get('auth-test', 'TestController@authTest');
    });
});
