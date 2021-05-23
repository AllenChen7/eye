<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            ''
        ]);
    }
}
