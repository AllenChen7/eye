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
        $api->get('logout', 'AuthController@logout');
        // 用户重置 token
        $api->get('refresh', 'AuthController@refresh');
        // 获取用户信息
        $api->get('me', 'AuthController@me')->name('me');
        // 创建用户
        $api->post('create', 'AuthController@create');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
        'prefix'     => 'user'
    ], function ($api) {
        // 用户列表
        $api->get('list', 'UserController@list');
        // 更新密码
        $api->post('update-password', 'UserController@updatePassword');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
        'prefix'     => 'address'
    ], function ($api) {
        // 地址
        $api->get('areas', 'AddressController@areas');
        // 省份
        $api->get('province', 'AddressController@province');
        // 市
        $api->get('city', 'AddressController@city');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
        'prefix'     => 'school'
    ], function ($api) {
        // 创建年级班级
        $api->post('create', 'SchoolController@create');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
    ], function ($api) {
        $api->get('auth-test', 'TestController@authTest');
    });
});
