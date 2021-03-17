<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WxSearchLog
 *
 * @property int $id
 * @property int $wx_user_id 微信用户ID
 * @property int $student_id 学生ID
 * @property string $l_degree 左眼度数
 * @property string $r_degree 右眼度数
 * @property int $class_data_id 班级ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog whereClassDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog whereLDegree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog whereRDegree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxSearchLog whereWxUserId($value)
 * @mixin \Eloquent
 */
class WxSearchLog extends Model
{
    //
}
