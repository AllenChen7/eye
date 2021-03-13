<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('class_data_id')->index()->comment('学校ID');
            $table->unsignedInteger('grade_id')->index()->comment('年级ID');
            $table->unsignedInteger('year_class_id')->index()->comment('年级ID');
            $table->string('name', 100)->comment('计划名称');
            $table->string('plan_date', 20)->comment('验光日期');
            $table->string('plan_user')->comment('验光负责人')->nullable();
            $table->string('remark', 200)->comment('备注')->nullable();
            $table->unsignedInteger('create_user_id')->index()->comment('创建人');
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
        Schema::dropIfExists('plans');
    }
}
