<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('class_data_id')->default(0)->comment('班级ID')->index();
            $table->unsignedInteger('province_id')->default(0)->comment('省ID')->index();
            $table->unsignedInteger('city_id')->default(0)->comment('市ID')->index();
            $table->string('name', 100)->comment('名称');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态')->index();
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
        Schema::dropIfExists('grades');
    }
}
