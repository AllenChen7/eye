<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
class ProvinceCityArea extends Model
{
    protected $table = 'province_city_area';
}
