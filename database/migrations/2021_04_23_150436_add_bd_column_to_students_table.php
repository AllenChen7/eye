<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBdColumnToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student', function (Blueprint $table) {
            //
            $table->decimal('pd', 8, 2)->default(0)->after('is_del')->comment('tj');
        });

        Schema::table('student_logs', function (Blueprint $table) {
            //
            $table->decimal('pd', 8, 2)->default(0)->after('is_del')->comment('tj');
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
            $table->dropColumn('pd');
        });

        Schema::table('student_logs', function (Blueprint $table) {
            $table->dropColumn('pd');
        });
    }
}
