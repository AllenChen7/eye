<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYearClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('grade_id')->comment('年级ID')->index();
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
        Schema::dropIfExists('year_classes');
    }
}
