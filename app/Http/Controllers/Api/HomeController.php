<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\ClassData;
use App\Models\Common;
use App\Models\Grade;
use App\Models\Plan;
use App\Models\Student;
use App\Models\YearClass;
use Illuminate\Http\Request;

class HomeController extends ApiController
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);
        $idArr = (new ClassData())->idArr();

        if (!$idArr) {
            return $this->successResponse();
        }

        $studentArr = Student::whereIn('class_data_id', $idArr)->get();
        $planDoneNums = 0;
        $planNotNums = 0;
        $myopiaNum = 0;
        $notMyopiaNum = 0;
        $unknownNums = 0;

        foreach ($studentArr as $value) {
            switch ($value['is_myopia']) {
                case Common::YES:
                    $myopiaNum++;
                    break;
                case Common::NO:
                    $notMyopiaNum++;
                    break;
                default:
                    $unknownNums++;
                    break;
            }

            switch ($value['plan_status']) {
                case Common::PLAN_STATUS_ACTIVE:
                    $planNotNums++;
                    break;
                case Common::PLAN_STATUS_DONE:
                    $planDoneNums++;
                    break;
            }
        }

        $type = auth()->user()->type;

        if (!$type) {
            $type = auth()->user()->power_type;
        }

        $query = Plan::whereIn('class_data_id', $idArr);
        $offset = $page <= 1 ? 0 : ($page - 1) * $limit;
        $rows = $query->limit($limit)->offset($offset)->get();

        foreach ($rows as $row) {
            $row['school'] = ClassData::where([
                    'id' => $row['class_data_id']
                ])->first()->name ?? '-';
            $row['class'] = YearClass::where([
                    'id' => $row['year_class_id']
                ])->first()->name ?? '-';
            $row['grade'] = Grade::where([
                    'id' => $row['grade_id']
                ])->first()->name ?? '-';
            $row['count'] = Student::where([
                'year_class_id' => $row['year_class_id']
            ])->count();
            $row['status_name'] = Common::planStatusArr()[$row['status']];

            if (strtotime($row['plan_date']) < time()) {
                $row['status_name'] = '超时未执行';
            }
        }

        return $this->successResponse([
            'plan_done_nums' => $planDoneNums,
            'plan_not_nums' => $planNotNums,
            'plan_count'    => $planDoneNums + $planNotNums,
            'myopia_nums' => $myopiaNum,
            'not_myopia_nums' => $notMyopiaNum,
            'unknown_nums' => $unknownNums,
            'myopia_count' => $myopiaNum + $notMyopiaNum + $unknownNums,
            'is_plan_edit' => $type == Common::TYPE_SCH ? 1 : 0,
            'rows'   => $rows
        ]);
    }
}
