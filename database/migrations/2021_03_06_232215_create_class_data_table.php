<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('province_id')->default(0)->comment('省ID')->index();
            $table->unsignedInteger('city_id')->default(0)->comment('市ID')->index();
            $table->unsignedInteger('create_user_id')->default(0)->comment('创建人')->index();
            $table->string('name', 100)->comment('名称');
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
        Schema::dropIfExists('class_data');
    }
}
