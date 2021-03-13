<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\ModelsData{
/**
 * App\ModelsData\Areas
 *
 * @property int $id
 * @property string $name 省市县名称
 * @property string $parent_id 父级id
 * @property string $type 类型
 * @method static \Illuminate\Database\Eloquent\Builder|Areas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Areas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Areas query()
 * @method static \Illuminate\Database\Eloquent\Builder|Areas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Areas whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Areas whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Areas whereType($value)
 * @mixin \Eloquent
 */
	class Areas extends \Eloquent {}
}

namespace App\ModelsData{
/**
 * App\ModelsData\UsersData
 *
 * @property int $id
 * @property string|null $name 用户名
 * @property string|null $phone 手机号
 * @property string|null $email 邮箱
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password 密码
 * @property int $status 用户状态，1有效，0无效
 * @property int $type 用户类型,权限等级
 * @property int $create_user_id 创建人
 * @property string|null $remark 备注
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData query()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $class_data_id 学校ID
 * @property int $city_id 市ID
 * @property int $province_id 省ID
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereClassDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereProvinceId($value)
 * @property int $area_id 县级用户ID
 * @property int $power_type 继承type
 * @property int $power_user_id 继承用户id
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData wherePowerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData wherePowerUserId($value)
 * @property int $is_del
 * @method static \Illuminate\Database\Eloquent\Builder|UsersData whereIsDel($value)
 */
	class UsersData extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AdminUser
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereUsername($value)
 * @mixin \Eloquent
 */
	class AdminUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ClassData
 *
 * @property int $id
 * @property int $province_id 省ID
 * @property int $city_id 市ID
 * @property int $create_user_id 创建人
 * @property string $name 名称
 * @property int $status 状态
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $area_id 县级用户ID
 * @method static \Illuminate\Database\Eloquent\Builder|ClassData whereAreaId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Grade[] $grade
 * @property-read int|null $grade_count
 */
	class ClassData extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Grade
 *
 * @property int $id
 * @property int $class_data_id 班级ID
 * @property int $province_id 省ID
 * @property int $city_id 市ID
 * @property string $name 名称
 * @property int $status 状态
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Grade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade query()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereClassDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\YearClass[] $yearClass
 * @property-read int|null $year_class_count
 */
	class Grade extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Plan
 *
 * @property int $id
 * @property int $class_data_id 学校ID
 * @property int $grade_id 年级ID
 * @property int $year_class_id 年级ID
 * @property string $name 计划名称
 * @property string $plan_date 验光日期
 * @property string|null $plan_user 验光负责人
 * @property string|null $remark 备注
 * @property int $create_user_id 创建人
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereClassDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan wherePlanDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan wherePlanUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereYearClassId($value)
 */
	class Plan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Post
 *
 * @property int $id
 * @property int $admin_user_id 作者ID
 * @property string $title 标题
 * @property string $desc 正文
 * @property string|null $image 配图
 * @property int $status 状态：0待发布，1已发布
 * @property int $view_nums 查看次数
 * @property int $notify_nums 提醒条数
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereAdminUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereNotifyNums($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereViewNums($value)
 * @mixin \Eloquent
 * @property-read \App\Models\AdminUser $adminUser
 */
	class Post extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProvinceCityArea
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProvinceCityArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProvinceCityArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProvinceCityArea query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name 省市县名称
 * @property string $parent_id 父级id
 * @property string $type 类型
 * @method static \Illuminate\Database\Eloquent\Builder|ProvinceCityArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProvinceCityArea whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProvinceCityArea whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProvinceCityArea whereType($value)
 */
	class ProvinceCityArea extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Student extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\YearClass
 *
 * @property int $id
 * @property int $grade_id 年级ID
 * @property string $name 名称
 * @property int $status 状态
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass query()
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $class_data_id 班级数据ID
 * @method static \Illuminate\Database\Eloquent\Builder|YearClass whereClassDataId($value)
 */
	class YearClass extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $phone 手机号
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @property int $status 用户状态，1有效，0无效
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @property string|null $remark 备注
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRemark($value)
 * @property int $type 用户类型,权限等级
 * @property int $create_user_id 创建人
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @property int $class_data_id 学校ID
 * @property int $city_id 市ID
 * @property int $province_id 省ID
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereClassDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvinceId($value)
 * @property int $area_id 县级用户ID
 * @property int $power_type 继承type
 * @property int $power_user_id 继承用户id
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePowerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePowerUserId($value)
 * @property int $is_del
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsDel($value)
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject {}
}

