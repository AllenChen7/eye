<?php

namespace App\ModelsData;


use App\Models\ClassData;
use App\Models\Common;
use App\Models\Grade;
use App\Models\Student;
use App\Models\YearClass;

class StudentData
{
    public $schoolId;
    public $gradeId;
    public $classId;
    public $name;
    public $idCard;
    public $studentCode;
    public $limit = 20;
    public $page = 1;

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

    public function rowCount()
    {
        return $this->baseQuery()->count();
    }

    public function rowData()
    {
        $offset = $this->page <= 1 ? 0 : ($this->page - 1) * $this->limit;

        $rows = $this->baseQuery()->limit($this->limit)->offset($offset)->get();

        foreach ($rows as &$row) {
            unset($row['updated_at']);
            $row['optometry_date'] = date('Y-m-d H:i:s');
            $row['sex_name'] = Common::sexArr()[$row['sex']];
            $row['years_old'] = Common::transYearOld($row['birthday']);
            $row['grade'] = Grade::where([
                'id' => $row['grader_id']
            ])->first()->name;
            $row['class'] = YearClass::where([
                'id' => $row['year_class_id']
            ])->first()->name;
            $row['school'] = ClassData::where([
                'id' => $row['class_data_id']
            ])->first()->name;
            $row['status_name'] = Common::studentStatusArr()[$row['status']];
            $row['join_school_date'] = '2020.09';
        }

        return $rows;
    }

    public function baseQuery()
    {
        $query = Student::where([
            'is_del' => Common::NO
        ])->orderByDesc('id');

        if ($this->schoolId) {
            $query->where([
                'class_data_id' => $this->schoolId
            ]);
        }

        if ($this->gradeId) {
            $query->where([
                'grade_id' => $this->gradeId
            ]);
        }

        if ($this->name) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }

        if ($this->idCard) {
            $query->where('id_card', 'like', '%' . $this->idCard . '%');
        }

        if ($this->classId) {
            $query->where([
                'year_class_id' => $this->classId
            ]);
        }

        if ($this->studentCode) {
            $query->where('student_code', 'like', '%' . $this->studentCode . '%');
        }

        return $query;
    }
}
