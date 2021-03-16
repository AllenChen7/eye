<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreateUserIdColumnToGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->unsignedInteger('create_user_id')->default(0)->index()->comment('创建人')->after('status');
        });

        Schema::table('year_classes', function (Blueprint $table) {
            $table->unsignedInteger('create_user_id')->default(0)->index()->comment('创建人')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn('create_user_id');
        });

        Schema::table('year_classes', function (Blueprint $table) {
            $table->dropColumn('create_user_id');
        });
    }
}
