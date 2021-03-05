<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\ProvinceCityArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Aoxiang\Pca\ProvinceCityArea as Pac;

class AddressController extends ApiController
{
    public function areas()
    {
        $cacheStr = 'address_area_list_cache';
        $areas = Cache::get($cacheStr, function () {
            return $this->dealAreas();
        });

        return $this->successResponse($areas);
    }

    public function province()
    {
        $provice = Pac::getProvinceList();

        return $this->successResponse($provice);
    }

    public function city(Request $request)
    {
        $city = Pac::getCityList($request->get('pid'));

        return $this->successResponse($city);
    }

    protected function dealAreas()
    {
        $areas = ProvinceCityArea::whereIn('type', ['province', 'city'])->get()->toArray();
        $provinceArr = [];

        foreach ($areas as $key => $item) {
            if ($item['parent_id'] == 0) {
                $provinceArr[] = $item;

                unset($areas[$key]);
            }
        }

        foreach ($provinceArr as &$item) {
            foreach ($areas as $key => $value) {
                if ($value['parent_id'] == $item['id']) {
                    $item['child'][] = $value;

                    unset($areas[$key]);
                }
            }
        }

        return $provinceArr;
    }
}
