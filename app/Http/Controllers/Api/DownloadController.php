<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Student;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function list()
    {

    }

    public function search(Request $request)
    {
        $schoolIdArr = $request->input('school_id', 0);
        $joinSchoolDate = $request->input('join_school_date', 0);
        $idCard = $request->input('id_card', 0);
        $gradeId = $request->input('grade_id', 0);
        $classId    = $request->input('class_id', 0);
        $name   = $request->input('name', '');
        $sex  = $request->input('sex');
        $code   = $request->input('code', '');
        $isM = $request->input('is_m');

        $student = Student::where([
            'is_del'    => Common::STATUS_DISABLED
        ]);

        if ($schoolIdArr) {
            $student->where([
                'class_data_id' => $schoolIdArr
            ]);
        }

        if ($joinSchoolDate) {
            $student->where([
                'join_school_date'  => $joinSchoolDate
            ]);
        }

        if ($idCard) {
            $student->where('id_card', 'like', '%' . $idCard . '%');
        }

        if ($gradeId) {
            $student->where([
                'grade_id'  => $gradeId
            ]);
        }

        if ($classId) {
            $student->where([
                'year_class_id' => $classId
            ]);
        }

        if ($name) {
            $student->where('name', 'like', '%' . $name . '%');
        }

        if ($sex) {
            $student->where([
                'set'   =>  $sex
            ]);
        }

        if ($code) {
            $student->where([
                'student_code'  => $code
            ]);
        }

        if ($isM) {
            $student->where([
                'is_myopia' => $isM
            ]);
        }

        $list = $student->get();

        dda($list);
    }
}
