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
        $api->post('wx/mini-login', 'WxController@wxMiniLogin');
        // 通过手机号进行登录
        $api->post('auth/login-by-phone', 'AuthController@loginByPhone');
        // 通过邮箱进行登录
        $api->post('auth/login-by-email', 'AuthController@loginByEmail');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth:wx',
        'prefix'     => 'wx'
    ], function ($api) {
        // 解密
        $api->post('decrypt-data', 'WxController@decryptData');
        // 列表
        $api->get('list', 'WxController@list');
        // 学生信息查询
        $api->post('search', 'WxController@search');
        // 更新保存 userInfo
        $api->post('update-user-info', 'WxController@userInfo');
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
        $api->post('create', 'AuthController@create')->middleware('permission:/authority/user:/authority-*');
        // 省级用户
        $api->get('province', 'AuthController@province');
        // 市级用户
        $api->get('city', 'AuthController@city');
        // 县级用户
        $api->get('area', 'AuthController@area');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
        'prefix'     => 'user'
    ], function ($api) {
        // 用户列表
        $api->get('list', 'UserController@list')->middleware('permission:/authority/user:/authority-*');
        // 更新密码
        $api->post('update-password', 'UserController@updatePassword');
        // 用户信息
        $api->get('view', 'UserController@view');
        // 重置密码
        $api->post('reset-password', 'UserController@resetPassword')->middleware('permission:/authority/user:/authority-*');
        // 用户删除
        $api->post('delete', 'UserController@delete')->middleware('permission:/authority/user:/authority-*');
        // 用户更新
        $api->post('update', 'UserController@update')->middleware('permission:/authority/user:/authority-*');
        // 调整用户状态
        $api->post('update-status', 'UserController@updateStatus')->middleware('permission:/authority/user:/authority-*');
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
        $api->post('create', 'SchoolController@create')->middleware('permission:/school-*:/school/grade-*:/school/grade-add');
        // 添加班级
        $api->post('add-class', 'SchoolController@addClass')->middleware('permission:/school-*:/school/grade-*:/school/grade-add');
        // 获取学校下的年级
        $api->get('grade', 'SchoolController@grade');
        // 获取年级下班级
        $api->get('class-data', 'SchoolController@classData');
        // 学校列表
        $api->get('list', 'SchoolController@list')->middleware('permission:/school-*:/school/index-*:/school/index-view');
        // 更新学校名称
        $api->post('update-school-name', 'SchoolController@updateSchoolName')->middleware('permission:/school-*:/school/index-*:/school/index-edit');
        // 删除年级
        $api->post('delete-grade', 'SchoolController@deleteGrade')->middleware('permission:/school-*:/school/grade-*:/school/grade-delete');
        // 删除班级
        $api->post('delete-class', 'SchoolController@deleteClass')->middleware('permission:/school-*:/school/grade-*:/school/grade-delete');
        // 年级列表
        $api->get('grade-list', 'SchoolController@gradeList')->middleware('permission:/school-*:/school/grade-*:/school/grade-view');
        // 查看年级
        $api->get('view-grade', 'SchoolController@viewGrade');
        // 学校状态修改
        $api->get('view-grade', 'SchoolController@viewGrade')->middleware('permission:/school-*:/school/index-*:/school/index-edit');
        // 修改学校名称
        $api->post('update-grade-name', 'SchoolController@updateGradeName')->middleware('permission:/school-*:/school/index-*:/school/index-edit');
        // 学校列表
        $api->get('school-list', 'SchoolController@schoolList');
        // 将学校置为无效
        $api->post('update-status', 'SchoolController@deleteSchool');
        // 删除
        $api->post('delete', 'SchoolController@delete');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
        'prefix'     => 'student'
    ], function ($api) {
        // 创建学生
        $api->post('create', 'StudentController@create')->middleware('permission:/student/index-*:/student/index-add');
        // 列表
        $api->get('list', 'StudentController@list')->middleware('permission:/student/index-*:/student/index-view');
        // 详情
        $api->get('view', 'StudentController@view');
        // 更新度数
        $api->post('update-degree', 'StudentController@updateDegree')->middleware('permission:/student/index-*:/student/index-edit');
        // 批量导入
        $api->post('import', 'StudentController@import')->middleware('permission:/student/index-*:/student/index-import');
        // 班级学生列表
        $api->get('class-student', 'StudentController@classStudent');
        // 更新
        $api->post('update', 'StudentController@update')->middleware('permission:/student/index-*:/student/index-edit');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
        'prefix'     => 'permission'
    ], function ($api) {
        // 获取权限等级
        $api->get('permission-list', 'PermissionController@permissionList')->middleware('permission:/authority-*:/authority/authSet');
        // 测试
//        $api->get('test', 'PermissionController@test');
        // 权限集
        $api->get('init-roles-list', 'PermissionController@initRolesList')->middleware('permission:/authority-*:/authority/character');
        // 详情
        $api->get('view', 'PermissionController@view')->middleware('permission:/authority-*:/authority/character');
        // 角色添加权限
        $api->post('add-permission', 'PermissionController@addPermission')->middleware('permission:/authority-*:/authority/character');
        // 添加角色
        $api->post('add-roles', 'PermissionController@addRoles')->middleware('permission:/authority-*:/authority/character');
        // 角色列表
        $api->get('roles-list', 'PermissionController@roleList')->middleware('permission:/authority-*:/authority/character');
        // 修改状态
        $api->post('update-status', 'PermissionController@updateStatus')->middleware('permission:/authority-*:/authority/character');
        // 删除
        $api->post('delete', 'PermissionController@delete')->middleware('permission:/authority-*:/authority/character');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
        'prefix'     => 'plan'
    ], function ($api) {
        // 创建验光计划
        $api->post('create', 'PlanController@create')->middleware('permission:/project/index-*:/project/index-add');
        // 验光计划列表
        $api->get('list', 'PlanController@list')->middleware('permission:/project/index-*:/project/index-view');
        // 删除
        $api->post('delete', 'PlanController@delete')->middleware('permission:/project/index-*:/project/index-delete');
        // 更新
        $api->post('update', 'PlanController@update')->middleware('permission:/project/index-*:/project/index-edit');
        // 计划详情
        $api->get('detail', 'PlanController@detail')->middleware('permission:/project/index-*:/project/index-edit');
        // 完成验光
        $api->post('done', 'PlanController@done')->middleware('permission:/project/index-*:/project/index-edit');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
        'prefix'     => 'version'
    ], function ($api) {
        // 检测是否有更新
        $api->get('check', 'VersionController@checkUpdate');
    });

    $api->group([
        'namespace'  => 'App\Http\Controllers\Api',
        'middleware' => 'jwt.auth',
    ], function ($api) {
        $api->get('auth-test', 'TestController@authTest');
    });
});
