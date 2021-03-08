<?php

namespace App\ModelsData;

use App\Models\ProvinceCityArea;
use Illuminate\Database\Eloquent\Model;

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
class Areas extends Model
{
    protected $table = 'province_city_area';

    /**
     * @param $cityId
     * @param $provinceId
     * @return ProvinceCityArea|\Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function areasDataByCityIdAndProvinceId($cityId, $provinceId)
    {
        return ProvinceCityArea::where([
            'id' => $cityId,
            'parent_id' => $provinceId
        ])->first();
    }
}
