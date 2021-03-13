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
use Illuminate\Support\Facades\Validator;

class PlanController extends ApiController
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|max:100',
            'plan_data' => 'required',
            'plan_user' => 'nullable|max:64',
            'remark' => 'nullable|max:200',
            'grade_id'  => 'required|exists:grades,id',
            'class_id' => 'required|exists:year_classes,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('验证错误', $validator->errors(), 422);
        }

        $model = new Plan();
        $model->name = $request->input('name');
        $model->plan_date = $request->input('plan_data');
        $model->plan_user = $request->input('plan_user');
        $model->remark = $request->input('remark');
        $model->grade_id = $request->input('grade_id');
        $model->year_class_id = $request->input('class_id');
        $model->create_user_id = auth()->id();

        $classInfo = YearClass::where([
            'id' => $request->input('class_id')
        ])->first();

        if ($classInfo['grade_id'] != $request->input('grade_id')) {
            return $this->errorResponse('验证错误', [
                'grade_id' => [
                    '班级不属于该年级'
                ]
            ], 422);
        }

        $model->class_data_id = $classInfo['class_data_id'];

        if ($model->save()) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    public function list(Request $request)
    {
        $name = $request->input('name');
        $gradeId = $request->input('grade_id');
        $classId = $request->input('class_id');
        $type = $request->input('type', 1);

        $query = Plan::where([
            'is_del' => Common::NO
        ])->orderByDesc('id');

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($gradeId) {
            $query->where([
                'grade_id' => $gradeId
            ]);
        }

        if ($classId) {
            $query->where([
                'year_class_id' => $classId
            ]);
        }

        $countRows = $query->get()->groupBy('status');
        $data = [];
        $statusArr = [
            1 => '进行中',
            2 => '已完成'
        ];

        foreach ($statusArr as $key => $value) {
            $data[] = [
                'name' => $value,
                'value' => $key,
                'is_default' => $type == $key ? 1 : 0,
                'count' => isset($countRows[$key]) ? count($countRows[$key]) : 0
            ];
        }

        $rows = $query->where([
            'status' => $type
        ])->get();

        foreach ($rows as $row) {
            $row['school'] = ClassData::where([
                'id' => $row['class_data_id']
            ])->first()->name;
            $row['class'] = YearClass::where([
                'id' => $row['year_class_id']
            ])->first()->name;
            $row['grade'] = Grade::where([
                'id' => $row['grade_id']
            ])->first()->name;
            $row['count '] = Student::where([
                'id' => $row['year_class_id']
            ])->count();
            $row['status_name'] = Common::planStatusArr()[$row['status']];

            if (strtotime($row['plan_date']) < time()) {
                $row['status_name'] = '超时未执行';
            }
        }

        return $this->successResponse([
            'count' => $data,
            'rows' => $rows
        ]);
    }
}
