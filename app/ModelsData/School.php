<?php

namespace App\ModelsData;


use App\Models\YearClass;

class School
{
    /**
     * @param $classArr
     * @return \Illuminate\Support\Collection
     */
    public static function yearClassByName($classArr)
    {
        return YearClass::whereIn('name', $classArr)->get();
    }
}
