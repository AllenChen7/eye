<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StudentLog
 *
 * @property int $id
 * @property int $student_id 学生ID
 * @property string $name 姓名
 * @property int $sex 性别,0未知，1男，2女
 * @property string $student_code 学号
 * @property string $id_card 身份证号
 * @property string $birthday 出生日期
 * @property int $class_data_id 学校ID
 * @property int $grade_id 年级ID
 * @property int $year_class_id 班级ID
 * @property int $create_user_id 创建人ID
 * @property int $is_myopia 是否近视，0不近视，1近视
 * @property int $is_glasses 是否佩戴眼镜，0不带，1带
 * @property int $glasses_type 眼镜类型0,普通眼镜，1隐形眼镜
 * @property int $status 状态，0开除，1正常，2休学中
 * @property int $join_school_date 入学日期
 * @property int $is_del 是否删除，0否，1删除
 * @property string $l_degree 左眼度数
 * @property string $l_sph 左眼球经度
 * @property string $l_cyl 左眼柱经度
 * @property string $l_axi 左眼轴位A
 * @property string $l_roc1 右眼曲率半径R1
 * @property string $l_roc2 右眼曲率半径R2
 * @property string $l_axis 右眼角膜轴位Axis
 * @property string $r_degree 右眼度数
 * @property string $r_sph 右眼球经度
 * @property string $r_cyl 右眼柱经度
 * @property string $r_axi 右眼轴位A
 * @property string $r_roc1 右眼曲率半径R1
 * @property string $r_roc2 右眼曲率半径R2
 * @property string $r_axis 右眼角膜轴位Axis
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereClassDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereGlassesType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereIdCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereIsDel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereIsGlasses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereIsMyopia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereJoinSchoolDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereLAxi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereLAxis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereLCyl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereLDegree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereLRoc1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereLRoc2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereLSph($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereRAxi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereRAxis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereRCyl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereRDegree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereRRoc1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereRRoc2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereRSph($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereStudentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog whereYearClassId($value)
 * @mixin \Eloquent
 * @property int $plan_id 计划ID
 * @method static \Illuminate\Database\Eloquent\Builder|StudentLog wherePlanId($value)
 */
class StudentLog extends Model
{
    //
}
