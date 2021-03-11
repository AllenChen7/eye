<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaIdColumnToClassDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_data', function (Blueprint $table) {
            $table->unsignedInteger('area_id')->default(0)->comment('县级用户ID')->index()->after('city_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_data', function (Blueprint $table) {
            $table->dropColumn('area_id');
        });
    }
}
