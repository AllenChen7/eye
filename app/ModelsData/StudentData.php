<?php

namespace App\ModelsData;


use App\Models\Common;
use App\Models\Student;

class StudentData
{
    /**
     * @param $idCard
     * @return Student|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function studentByIdCard($idCard)
    {
        return Student::where([
            'id_card' => $idCard,
            'is_del'  => Common::NO
        ])->first();
    }
}
