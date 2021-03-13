<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @mixin \Eloquent
 */
class Plan extends Model
{
    //
}
