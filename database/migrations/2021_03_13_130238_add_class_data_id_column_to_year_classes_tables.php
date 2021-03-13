<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassDataIdColumnToYearClassesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('year_classes', function (Blueprint $table) {
            $table->unsignedInteger('class_data_id')->index()->comment('班级数据ID')->after('grade_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('year_classes', function (Blueprint $table) {
            $table->dropColumn('class_data_id');
        });
    }
}
