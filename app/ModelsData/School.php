<?php

namespace App\ModelsData;


use App\Models\Common;
use App\Models\Grade;
use App\Models\YearClass;

class School
{
    /**
     * @param $classArr
     * @return \Illuminate\Support\Collection
     */
    public static function yearClassByName($classArr, $gradeId)
    {
        return YearClass::whereIn('name', $classArr)->where([
            'grade_id' => $gradeId
        ])->get();
    }

    /**
     * @param $classDataId
     * @return Grade[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function gradeListByClassDataId($classDataId)
    {
        return Grade::where([
            'class_data_id' => $classDataId,
            'status'        => Common::STATUS_ACTIVE
        ])->get();
    }

    /**
     * @param $gradeId
     * @return YearClass[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function classListByGradeId($gradeId)
    {
        return YearClass::where([
            'grade_id' => $gradeId,
            'status'   => Common::STATUS_ACTIVE
        ])->get();
    }
}
