<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlanIdColumnToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedInteger('plan_id')->index()->comment('计划ID')->default(0)->after('status');
            $table->unsignedTinyInteger('plan_status')->index()->comment('计划状态, 0未验光计划、1开启验光计划、2验光完毕、3验光废弃')->default(0)->after('status');
        });

        Schema::table('student_logs', function (Blueprint $table) {
            $table->unsignedInteger('plan_id')->index()->comment('计划ID')->default(0)->after('status');
            $table->unsignedTinyInteger('plan_status')->index()->comment('计划状态, 0未验光计划、1开启验光计划、2验光完毕、3验光废弃')->default(2)->after('status');
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
            $table->dropColumn('plan_id');
            $table->dropColumn('plan_status');
        });

        Schema::table('student_logs', function (Blueprint $table) {
            $table->dropColumn('plan_id');
            $table->dropColumn('plan_status');
        });
    }
}
