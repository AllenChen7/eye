<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('admin_user_id')->index()->comment('作者ID');
            $table->string('title')->comment('标题');
            $table->text('desc')->comment('正文');
            $table->string('image')->nullable()->comment('配图');
            $table->unsignedInteger('status')->default(0)->comment('状态：0待发布，1已发布')->index();
            $table->unsignedInteger('view_nums')->default(0)->comment('查看次数');
            $table->unsignedInteger('notify_nums')->default(0)->comment('提醒条数');
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
        Schema::dropIfExists('posts');
    }
}
