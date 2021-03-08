<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 */
class ClassData extends Model
{
    protected $fillable = [
        'province_id', 'city_id', 'create_user_id', 'name', 'status'
    ];
}
