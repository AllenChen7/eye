<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
class Grade extends Model
{
    protected $hidden = [
        'created_at', 'updated_at', 'status', 'class_data_id', 'province_id', 'city_id'
    ];

    public function yearClass()
    {
        return $this->hasMany('App\Models\YearClass');
    }
}
