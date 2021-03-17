<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWxSearchLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_search_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('wx_user_id')->index()->comment('微信用户ID');
            $table->unsignedInteger('student_id')->index()->comment('学生ID');
            $table->decimal('l_degree', 8, 2)->comment('左眼度数');
            $table->decimal('r_degree', 8, 2)->comment('右眼度数');
            $table->unsignedInteger('class_data_id')->comment('班级ID');
            $table->timestamps();
        });

        Schema::table('wx_users', function (Blueprint $table) {
            $table->string('nums')->default(1)->comment('查询次数')->after('status');
            $table->unsignedInteger('class_data_id')->default(0)->comment('学校ID')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wx_search_logs');

        Schema::table('wx_users', function (Blueprint $table) {
            $table->dropColumn('nums');
            $table->dropColumn('class_data_id');
        });
    }
}
