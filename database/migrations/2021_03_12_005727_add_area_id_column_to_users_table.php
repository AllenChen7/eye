<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaIdColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('power_user_id')->default(0)->comment('继承用户id')->index()->after('province_id');
            $table->unsignedTinyInteger('power_type')->default(0)->comment('继承type')->index()->after('province_id');
            $table->unsignedInteger('area_id')->default(0)->comment('县级用户ID')->index()->after('province_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('power_user_id');
            $table->dropColumn('power_type');
            $table->dropColumn('area_id');
        });
    }
}
