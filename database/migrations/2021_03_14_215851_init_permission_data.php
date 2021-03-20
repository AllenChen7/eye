<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InitPermissionData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // 创建权限
        Permission::create(['name' => 'drm-*']); // 权限管理
        Permission::create(['name' => 'drm-roles']); // 角色管理
        Permission::create(['name' => 'drm-users']); // 用户管理
        Permission::create(['name' => 'drm-super-users']); // 管理员管理
        Permission::create(['name' => 'drm-Permissions']); // 权限集管理

        Permission::create(['name' => 'school-*']); // 学校管理
        Permission::create(['name' => 'school-school-*']); // 学校管理
        Permission::create(['name' => 'school-school-view']); // 查看
        Permission::create(['name' => 'school-school-add']); // 新增
        Permission::create(['name' => 'school-school-update']); // 编辑
        Permission::create(['name' => 'school-school-delete']); // 删除
        Permission::create(['name' => 'school-grade-*']); // 班级管理
        Permission::create(['name' => 'school-grade-view']); // 查看
        Permission::create(['name' => 'school-grade-add']); // 查看
        Permission::create(['name' => 'school-grade-update']); // 查看
        Permission::create(['name' => 'school-grade-delete']); // 查看

        Permission::create(['name' => 'student-*']); // 学生管理
        Permission::create(['name' => 'student-view']); //
        Permission::create(['name' => 'student-add']); //
        Permission::create(['name' => 'student-update']); //
        Permission::create(['name' => 'student-delete']); //
        Permission::create(['name' => 'student-import']); //
        Permission::create(['name' => 'student-export']); //

        Permission::create(['name' => 'plan-*']); // 验光计划
        Permission::create(['name' => 'plan-view']); //
        Permission::create(['name' => 'plan-add']); //
        Permission::create(['name' => 'plan-update']); //
        Permission::create(['name' => 'plan-delete']); //

        Permission::create(['name' => 'wx-*']); // 家长管理
        Permission::create(['name' => 'wx-view']); //
        Permission::create(['name' => 'wx-update']); //

        Permission::create(['name' => 'big-*']); // 大数据统计
        Permission::create(['name' => 'big-view']); // big data

        Permission::create(['name' => 'setting-*']); //设置
        Permission::create(['name' => 'setting-optometry']); //设置

        // create roles and assign created permissions

        $role = Role::create(['name' => 'xm']);
        $role->givePermissionTo(Permission::all());

        Role::create(['name' => 'province']);
        Role::create(['name' => 'city']);
        Role::create(['name' => 'area']);
        Role::create(['name' => 'school']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
