<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Student
 *
 * @property int $id
 * @property string $name 姓名
 * @property int $sex 性别,0未知，1男，2女
 * @property string $student_code 学号
 * @property string $id_card 身份证号
 * @property string $birthday 出生日期
 * @property int $class_data_id 学校ID
 * @property int $grader_id 年级ID
 * @property int $year_class_id 班级ID
 * @property int $is_myopia 是否近视，0不近视，1近视
 * @property int $is_glasses 是否佩戴眼镜，0不带，1带
 * @property int $glasses_type 眼镜类型0,普通眼镜，1隐形眼镜
 * @property int $status 状态，0开除，1正常，2休学中
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
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereClassDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereGlassesType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereGraderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereIdCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereIsDel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereIsGlasses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereIsMyopia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLAxi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLAxis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLCyl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLDegree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLRoc1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLRoc2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLSph($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereRAxi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereRAxis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereRCyl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereRDegree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereRRoc1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereRRoc2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereRSph($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereStudentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereYearClassId($value)
 * @mixin \Eloquent
 * @property int $create_user_id 创建人ID
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreateUserId($value)
 * @property int $grade_id 年级ID
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereGradeId($value)
 * @property string $join_school_date 入学年份
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereJoinSchoolDate($value)
 */
class Student extends Model
{
    protected  $fillable = ['name', 'sex', 'student_code', 'id_card', 'birthday', 'class_data_id', 'grade_id',
        'year_class_id', 'is_myopia', 'is_glasses', 'glasses_type', 'status', 'is_del', 'l_degree', 'l_sph', 'l_cyl',
        'l_axi', 'l_roc1', 'l_roc2', 'l_axis', 'r_degree', 'r_sph', 'r_cyl', 'r_axi', 'r_roc1', 'r_roc2', 'r_axis',
        'create_user_id'];

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function setGlassesTypeAttribute($value)
    {
        $this->attributes['glasses_type'] = (int)$value;
    }

    public function setLDegreeAttribute($value)
    {
        $this->attributes['l_degree'] = (int)$value;
    }

    public function setRDegreeAttribute($value)
    {
        $this->attributes['r_degree'] = (int)$value;
    }
}
