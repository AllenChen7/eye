<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->comment('姓名');
            $table->unsignedTinyInteger('sex')->default(0)->comment('性别,0未知，1男，2女')->index();
            $table->string('student_code', 32)->default(0)->comment('学号')->index();
            $table->string('id_card', 32)->default(0)->comment('身份证号')->index();
            $table->string('birthday', 12)->default(0)->comment('出生日期');
            $table->unsignedInteger('class_data_id')->comment('学校ID')->index();
            $table->unsignedInteger('grade_id')->comment('年级ID')->index();
            $table->unsignedInteger('year_class_id')->comment('班级ID')->index();
            $table->unsignedInteger('create_user_id')->comment('创建人ID')->index();
            $table->unsignedTinyInteger('is_myopia')->default(0)->comment('是否近视，0不近视，1近视')->index();
            $table->unsignedTinyInteger('is_glasses')->default(0)->comment('是否佩戴眼镜，0不带，1带')->index();
            $table->unsignedTinyInteger('glasses_type')->default(0)->comment('眼镜类型0,普通眼镜，1隐形眼镜');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态，0开除，1正常，2休学中')->index();
            $table->unsignedTinyInteger('is_del')->default(0)->comment('是否删除，0否，1删除')->index();
            $table->decimal('l_degree', 8, 2)->default(0)->comment('左眼度数');
            $table->decimal('l_sph', 8, 2)->default(0)->comment('左眼球经度');
            $table->decimal('l_cyl', 8, 2)->default(0)->comment('左眼柱经度');
            $table->decimal('l_axi', 8, 2)->default(0)->comment('左眼轴位A');
            $table->decimal('l_roc1', 8, 2)->default(0)->comment('右眼曲率半径R1');
            $table->decimal('l_roc2', 8, 2)->default(0)->comment('右眼曲率半径R2');
            $table->decimal('l_axis', 8, 2)->default(0)->comment('右眼角膜轴位Axis');
            $table->decimal('r_degree', 8, 2)->default(0)->comment('右眼度数');
            $table->decimal('r_sph', 8, 2)->default(0)->comment('右眼球经度');
            $table->decimal('r_cyl', 8, 2)->default(0)->comment('右眼柱经度');
            $table->decimal('r_axi', 8, 2)->default(0)->comment('右眼轴位A');
            $table->decimal('r_roc1', 8, 2)->default(0)->comment('右眼曲率半径R1');
            $table->decimal('r_roc2', 8, 2)->default(0)->comment('右眼曲率半径R2');
            $table->decimal('r_axis', 8, 2)->default(0)->comment('右眼角膜轴位Axis');
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
        Schema::dropIfExists('students');
    }
}
