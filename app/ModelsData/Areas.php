<?php

namespace App\ModelsData;

use App\Models\ProvinceCityArea;
use Illuminate\Database\Eloquent\Model;

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
