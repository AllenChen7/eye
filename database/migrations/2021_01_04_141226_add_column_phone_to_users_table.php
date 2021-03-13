<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPhoneToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->comment('邮箱')->change();
            $table->string('phone', 64)->nullable()->unique()->comment('手机号')->after('name');
            $table->tinyInteger('status')->default(1)->comment('用户状态，1有效，0无效')->after('password');
            $table->string('password')->nullable()->comment('密码')->change();
            $table->string('name')->nullable()->comment('用户名')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('status');
        });
    }
}
