<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWxUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_users', function (Blueprint $table) {
            $table->id();
            $table->string('openid', 64)->comment('openid')->index();
            $table->string('password', 64)->default(Hash::make('password'))->comment('password');
            $table->string('union_id', 64)->comment('union_id')->nullable()->index();
            $table->string('session_key', 64)->nullable()->comment('session key');
            $table->string('nickname', 64)->nullable()->comment('用户昵称');
            $table->unsignedTinyInteger('gender')->default(0)->comment('性别，1男2女');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('province', 32)->nullable()->comment('省');
            $table->string('country', 32)->nullable()->comment('国家');
            $table->string('city', 32)->nullable()->comment('市');
            $table->string('language', 32)->nullable()->comment('语言');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wx_users');
    }
}
