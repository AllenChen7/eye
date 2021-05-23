<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPlanDateToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('plan_date', 4)->after('plan_id')->index()->default(0)->comment('验光年份');
        });

        Schema::table('student_logs', function (Blueprint $table) {
            $table->string('plan_date', 4)->after('plan_id')->index()->default(0)->comment('验光年份');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('plan_date');
        });

        Schema::table('student_logs', function (Blueprint $table) {
            $table->dropColumn('plan_date');
        });
    }
}
