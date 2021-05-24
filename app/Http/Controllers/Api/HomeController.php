<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\ClassData;
use App\Models\Common;
use App\Models\Grade;
use App\Models\Plan;
use App\Models\Student;
use App\Models\StudentLog;
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

    public function home(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $schoolIdArr = $request->input('school_id_arr');
        $studentList = Student::where([
            'plan_date' => $year,
            'class_data_id' => $schoolIdArr
        ])->get()->toArray();
        $studentOldList = StudentLog::where([
            'plan_date' => $year - 1,
            'class_data_id' => $schoolIdArr
        ])->get()->toArray();

        $yearGroup = [];
        $sexGroup = [];
        $gradeGroup = [];
        $randArr = [
            '0~25' => 0,
            '25~100' => 0,
            '100~200' => 0,
            '200~300' => 0,
            '300~500' => 0,
            '500~800' => 0,
            '800~1000' => 0,
            '1000' => 0,
        ];
        $schoolGroup = [];
        $newCount = 0;
        $oldCount = 0;

        foreach ($studentOldList as $item) {
            if ($item['is_myopia'] == Common::NO) { // 先以此判断是否近视
                $oldCount++;
            }
        }

        foreach ($studentList as $item) {
            if ($item['is_myopia'] == Common::NO) { // 先以此判断是否近视
                $sexGroup[$item['sex']][] = $item;
                $gradeGroup[$item['grade_id']][] = $item;
                $yearGroup[Common::transYearOld($item['birthday'])][] = $item;
                $newCount++;
            }

            $schoolGroup[$item['year_class_id']][] = $item;

            if ($item['l_degree'] < 25 || $item['r_degree'] < 25) {
                $randArr['0~25']++;
            } elseif ($item['l_degree'] < 100 || $item['r_degree'] < 100) {
                $randArr['25~100']++;
            } elseif ($item['l_degree'] < 200 || $item['r_degree'] < 200) {
                $randArr['100~200']++;
            } elseif ($item['l_degree'] < 300 || $item['r_degree'] < 300) {
                $randArr['200~300']++;
            } elseif ($item['l_degree'] < 500 || $item['r_degree'] < 500) {
                $randArr['300~500']++;
            } elseif ($item['l_degree'] < 800 || $item['r_degree'] < 800) {
                $randArr['500~800']++;
            } elseif ($item['l_degree'] < 1000 || $item['r_degree'] < 1000) {
                $randArr['800~1000']++;
            } else {
                $randArr['1000']++;
            }
        }
        // 公式：（今年数-上年数）÷上年数×100%即可算出
        $rate = $oldCount ? ($newCount - $oldCount) / $oldCount * 100 : 100;

        $yearGroupArr = [];

        foreach ($yearGroup as $key => $item) {
            $yearGroupArr[] = [
                'name' => $key . '岁',
                'value' => count($item)
            ];
        }

        $sexGroupArr = [];
        foreach ($sexGroup as $key => $item) {
            $sexGroupArr[] = [
                'name' => Common::sexArr()[$key],
                'value' => count($item)
            ];
        }

        $gradeGroupArr = [];

        foreach ($gradeGroup as $key => $item) {
            $gradeGroupArr['keys'][] = $key;
            $gradeGroupArr['values'][] = [
                'name' => $key,
                'value' => count($item)
            ];
        }

        if (isset($gradeGroupArr['keys']) && $gradeGroupArr['keys']) {
            $gradeArr = Grade::where([
                'id' => $gradeGroupArr['keys']
            ])->get()->pluck('name', 'id');

            foreach ($gradeGroupArr['keys'] as &$item) {
                $item = $gradeArr[$item] ?? $item;
            }

            foreach ($gradeGroupArr['values'] as &$value) {
                $value['name'] = $gradeArr[$value['name']] ?? $value['name'];
            }
        }

        $schoolGroupArr = [];

        foreach ($schoolGroup as $s => $school) {
            $count = count($school);
            $isM = 0;

            foreach ($school as $sch) {
                if ($sch['is_myopia'] == Common::NO) {
                    $isM++;
                }
            }

            $schoolGroupArr[] = [
                'name' => $s,
                'ratio' => round($count <= 0 ? 0 : $isM / $count * 100, 2),
                'isM'   =>  $isM
            ];
        }

        $schoolGroupArr = array_reverse(\Arr::sort($schoolGroupArr, function ($val) {
            return $val['ratio'];
        }));

        $schoolRatioArr = [];

        foreach ($schoolGroupArr as $k => $v) {
            if ($k > 2) {
                break;
            }

            $school = ClassData::whereId($v['name'])->select('name')->first();
            $v['name'] = $school['name'] ?? $v['name'];
            $schoolRatioArr[] = $v;
        }

        $isMRateArr = [
            'now' => $newCount,
            'old' => $oldCount,
            'rate'=> $rate
        ];

        $randLabelArr = [];
        foreach ($randArr as $r => $rand) {
            $randLabelArr[] = [
                'value' => $rand,
                'name'  => $r
            ];
        }

        return $this->successResponse([
            'yearGroupArr'  => $yearGroupArr,
            'sexGroupArr'   => $sexGroupArr,
            'gradeGroupArr' => $gradeGroupArr['values'] ?? [],
            'randArr'       => $randLabelArr,
            'schoolRatioArr'    => $schoolRatioArr,
            'isMRateArr'       => $isMRateArr
        ]);
    }
}
