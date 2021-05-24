<?php

namespace App\Http\Controllers\Api;

use App\Exports\StudentExport;
use App\Http\Controllers\ApiController;
use App\Models\Common;
use App\Models\Download;
use App\Models\Student;
use App\ModelsData\StudentData;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DownloadController extends ApiController
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);
        $offset = $page <= 1 ? 0 : ($page - 1) * $limit;
        $list = Download::where([
            'user_id' => auth()->id()
        ])->limit($limit)->offset($offset)->get();

        return $this->successResponse($list);
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
            'is_del'    => Common::STATUS_DISABLED,
            'class_data_id' => $schoolIdArr
        ]);

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
        $data = [];

        foreach ($list as $l) {
            dd($l->grade);
            $data[] = [
                $l['name'],
                "\t" . $l['id_card'],
                Common::sexArr()[$l['sex']],
                $l['birthday'],
                $l['join_school_date'],
                "\t" . $l['student_code'],
                $l['grade_id'],
                $l['year_class_id'],
                Common::isMyopiaArr()[$l['is_myopia']],
                $l['is_myopia'] == Common::YES ? '' : Common::isArr()[$l['is_glasses']],
                $l['is_myopia'] == Common::YES ? '' : Common::glaType()[$l['glasses_type']],
                $l['l_degree'],
                $l['r_degree'],
            ];
        }

        $data = array_merge([StudentData::excelTitle()], $data);
        $columnArr = [
            'B' => NumberFormat::FORMAT_TEXT
        ];
        $fileName = '学生信息' . date('Y-m-d-H-i-s') . rand(100, 999) . '.xlsx';
        $sRes = (new StudentExport($data, $columnArr))->store($fileName, 'public');

        if ($sRes) {
            $url = asset('storage/' . $fileName);
            $model = new Download();
            $model->name = $fileName;
            $model->url = $url;
            $model->user_id = auth()->id();

            if ($model->save()) {
                return $this->successResponse($model);
            } else {
                return $this->errorResponse();
            }
        } else {
            return $this->errorResponse();
        }
    }
}
